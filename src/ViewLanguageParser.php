<?php
require_once("File.php");
require_once("ExpressionParser.php");
require_once("TagParser.php");
require_once("ViewCompilation.php");
require_once("taglib/Standard/loader.php");
require_once("taglib/System/loader.php");

/**
 * Performs the logic of view language parsing, delegating to tag and expressions parser.
 */
class ViewLanguageParser {
	private $strTemplatesFolder;
	private $strTemplatePath;
	private $strTemplatesExtension;
	private $strTagLibFolder;
	
	/**
	 * Constructs a view language parser.
	 * 
	 * @param string $strTemplatesFolder Location of templates folder on disk.
	 * @param string $strTemplatePath Location of template file relative to views folder (without extension). 
	 * @param string $strTemplatesExtension Extension of template files without dot (eg: php). 
	 * @param string $strTagLibFolder Location of user-defined tag libraries folder.
	 */
	public function __construct($strTemplatesFolder, $strTemplatePath, $strTemplatesExtension, $strTagLibFolder = "") {
		$this->strTemplatesFolder = $strTemplatesFolder;
		$this->strTemplatePath = $strTemplatePath;
		$this->strTemplatesExtension = $strTemplatesExtension;
		$this->strTagLibFolder = $strTagLibFolder;
	}
	
	/**
	 * Parses input string of view tags & expressions, writes results in a compilation file, then returns that file.
	 * 
	 * @param string $strCompilationsFolder Absolute location of compilations folder on disk.
	 * @param string $strOutputStream Response stream contents before view language constructs were parsed.
	 * @return string Compilation file name, containing response stream after view language constructs were parsed.
	 */
	public function compile($strCompilationsFolder, $strOutputStream="") {
		// opens existing compilation (if exists)
		$objViewCompilation = new ViewCompilation($strCompilationsFolder, $this->strTemplatePath, $this->strTemplatesExtension);
		
		// if compilation components haven't changed, do not go further
		if(!$objViewCompilation->hasChanged()) {
			return $objViewCompilation->getCompilationPath();
		}
		
		// includes dependant tree of templates
		$objImportTag = new SystemImportTag($this->strTemplatesFolder, $this->strTemplatesExtension, $objViewCompilation);
		$strOutputStream = $objImportTag->parse($this->strTemplatePath, $strOutputStream);
		
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
