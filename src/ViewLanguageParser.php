<?php
require_once("File.php");
require_once("ExpressionParser.php");
require_once("TagParser.php");
require_once("AbstractNonParsableTag.php");
require_once("taglib/StandardTags/loader.php");
require_once("taglib/SystemTags/loader.php");

/**
 * Performs the logic of view language parsing, delegating to tag and expressions parser.
 */
class ViewLanguageParser {
	private $strTemplatesFolder;
	private $strTemplatePath;
	private $strTemplatesExtension;
	
	/**
	 * Constructs a view language parser.
	 * 
	 * @param string $strTemplatesFolder Location of templates folder on disk.
	 * @param string $strTemplatePath Location of template file relative to views folder (without extension). 
	 * @param string $strTemplatesExtension Extension of template files without dot (eg: php). 
	 */
	public function __construct($strTemplatesFolder, $strTemplatePath, $strTemplatesExtension) {
		$this->strTemplatesFolder = $strTemplatesFolder;
		$this->strTemplatePath = $strTemplatePath;
		$this->strTemplatesExtension = $strTemplatesExtension;
	}
	
	/**
	 * Parses input string of view tags & expressions, writes results in a compilation file, then returns that file.
	 * 
	 * @param string $strCompilationsFolder Absolute location of compilations folder on disk.
	 * @param string $strOutputStream Response stream contents before view language constructs were parsed.
	 * @return string Compilation file name, containing response stream after view language constructs were parsed.
	 */
	public function compile($strCompilationsFolder, $strOutputStream="") {	
		// includes dependant tree of templates
		$objImportTag = new SystemImportTag($this->strTemplatesFolder, $this->strTemplatesExtension);
		$strOutputStream = $objImportTag->parse($this->strTemplatePath, $strOutputStream);
		$intViewModifiedTime = $objImportTag->getModifiedTime();
		
		// get compilation
	    $strCompilationFile = $strCompilationsFolder."/".$this->strTemplatePath.".".$this->strTemplatesExtension;
		$objCompilation = new File($strCompilationFile);
		if($objCompilation->exists()) {
			$intCompilationModifiedTime = $objCompilation->getModificationTime();
			if($intCompilationModifiedTime >= $intViewModifiedTime) {
				return $strCompilationFile; // file already compiled and not changed
			}
		}
		
		// start looking for tags whose values should be escaped
		$objEscapeTag = new SystemEscapeTag();
		$blnHasEscapedContent = $objEscapeTag->hasContent($strOutputStream);
		
		// backup escaped content
		if($blnHasEscapedContent) $objEscapeTag->backup($strOutputStream);

		// run tag parser
		$objTagParser = new TagParser();
		$strOutputStream=$objTagParser->parse($strOutputStream);
		
		// run expression parser
		$objExpressionParser = new ExpressionParser();
		$strOutputStream=$objExpressionParser->parse($strOutputStream);
		
		// restore escaped content
		if($blnHasEscapedContent) $objEscapeTag->restore($strOutputStream);
		
		// save compilation
		$objCompilation->putContents($strOutputStream);
		
		return $strCompilationFile;
	}
}
