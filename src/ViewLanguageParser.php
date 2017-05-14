<?php
require_once("File.php");
require_once("ExpressionParser.php");
require_once("TagParser.php");
require_once("ViewCompilation.php");
require_once("taglib/Std/loader.php");
require_once("taglib/Macros/loader.php");

/**
 * Performs the logic of view language parsing, delegating to tag and expressions parser.
 */
class ViewLanguageParser {
	private $strTemplatesFolder;
	private $strCompilationsFolder;
	private $strTemplatesExtension;
	private $strTagLibFolder;
	
	/**
	 * Constructs a view language parser.
	 * 
	 * @param string $strTemplatesFolder Relative path to templates folder on disk.
	 * @param string $strTemplatesExtension Extension of template files without dot (eg: php).
	 * @param string $strCompilationsFolder Absolute path to compilations folder on disk 
	 * @param string $strTagLibFolder Relative path to user-defined tag libraries folder.
	 */
	public function __construct($strTemplatesFolder, $strTemplatesExtension, $strCompilationsFolder, $strTagLibFolder = "") {
		$this->strTemplatesFolder = $strTemplatesFolder;
		$this->strTemplatesExtension = $strTemplatesExtension;
		$this->strCompilationsFolder= $strCompilationsFolder;
		$this->strTagLibFolder = $strTagLibFolder;
	}
	
	/**
	 * Compiles ViewLanguage instructions in input file/string into PHP, saves global view into a compilation file, then returns location to that file.
	 * 
	 * @param string $strTemplatePath Relative path to template file that needs to be compiled within templates folder (without extension). 
	 * @param string $strOutputStream Response stream contents before view language constructs were parsed.
	 * @return string Compilation file name, containing response stream after view language constructs were parsed.
	 */
	public function compile($strTemplatePath, $strOutputStream="") {
		// opens existing compilation (if exists)
		$objViewCompilation = new ViewCompilation($this->strCompilationsFolder, $strTemplatePath, $this->strTemplatesExtension);
		
		// if compilation components haven't changed, do not go further
		if(!$objViewCompilation->hasChanged()) {
			return $objViewCompilation->getCompilationPath();
		}
		
		// includes dependant tree of templates
		$objImportTag = new SystemImportTag($this->strTemplatesFolder, $this->strTemplatesExtension, $objViewCompilation);
		$strOutputStream = $objImportTag->parse($strTemplatePath, $strOutputStream);
		
		// start looking for tags whose values should be escaped
		$objEscapeTag = new SystemEscapeTag();
		$blnHasEscapedContent = $objEscapeTag->hasContent($strOutputStream);
		
		// backup escaped content
		if($blnHasEscapedContent) $objEscapeTag->removeContent($strOutputStream);

		// run tag parser
		$objTagParser = new TagParser($this->strTagLibFolder, $objViewCompilation);
		$strOutputStream=$objTagParser->parse($strOutputStream);
		
		// run expression parser
		$objExpressionParser = new ExpressionParser();
		$strOutputStream=$objExpressionParser->parse($strOutputStream);
		
		// restore escaped content
		if($blnHasEscapedContent) $objEscapeTag->restoreContent($strOutputStream);
		
		// saves new compilation
		$objViewCompilation->save($strOutputStream);
		
		return $objViewCompilation->getCompilationPath();
	}
}
