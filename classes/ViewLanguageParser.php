<?php
require_once("File.php");
require_once("ExpressionParser.php");
require_once("TagParser.php");
require_once("AbstractNonParsableTag.php");
require_once("taglib/StandardTags/loader.php");
require_once("taglib/SystemTags/loader.php");

/**
 * Performs the logic of view language parsing, delegating to tag and expressions parser
 */
class ViewLanguageParser {
	private $strViewsFolder;
	private $strViewFile;
	private $strCompilationsFolder;
	private $strCompilationFile;
	
	/**
	 * Constructs a view language parser.
	 * 
	 * @param string $strViewsFolder Absolute location of views folder on disk.
	 * @param string $strViewFile Location of view file relative to views folder. 
	 * @param string $strCompilationsFolder Absolute location of compilations folder on disk.
	 */
	public function __construct($strViewsFolder, $strViewFile, $strCompilationsFolder) {
		$this->strCompilationsFolder = $strCompilationsFolder;
		$this->strViewsFolder = $strViewsFolder;
		$this->strViewFile = $strViewsFolder."/".$strViewFile.".php";
		$this->strCompilationFile = $strCompilationsFolder."/".$strViewFile.".php";
	}
	
	/**
	 * Parses input string of view tags & expressions, writes results in a compilation file, then returns that file.
	 * 
	 * @param string $strOutput Response stream contents before view language constructs were parsed.
	 * @return string Compilation file name, containing response stream after view language constructs were parsed.
	 */
	public function parse($strOutputStream) {	
		// get view
		$objView = new File($this->strViewFile);
		$objImportTag = new SystemImportTag($this->strViewsFolder, $objView->getModificationTime());
		$strOutputStream = $objImportTag->parse($strOutputStream);
		$intViewModifiedTime = $objImportTag->getModifiedTime();
		$objImportTag = null;
		
		// get compilation
		$objCompilation = new File($this->strCompilationFile);
		if($objCompilation->exists()) {
			$intCompilationModifiedTime = $objCompilation->getModificationTime();
			if($intCompilationModifiedTime >= $intViewModifiedTime) {
				return $this->strCompilationFile; // file already compiled and not changed
			}
		}
		
		// start looking for tags whose values should be escaped
		$objEscapeTag = new SystemEscapeTag();
		$objScriptTag = new SystemScriptTag();
		$objStyleTag = new SystemStyleTag();
		
		// remove escaped content
		if($objEscapeTag->hasContent($strOutputStream)) $strOutputStream = $objEscapeTag->removeContent($strOutputStream);
		if($objScriptTag->hasContent($strOutputStream)) $strOutputStream = $objScriptTag->removeContent($strOutputStream);
		if($objStyleTag->hasContent($strOutputStream)) 	$strOutputStream = $objStyleTag->removeContent($strOutputStream);
				
		// run tag parser
		$objTagParser = new TagParser();
		$strOutputStream=$objTagParser->parse($strOutputStream);
		
		// run expression parser
		$objExpressionParser = new ExpressionParser();
		$strOutputStream=$objExpressionParser->parse($strOutputStream);
		
		// restore escaped content
		if($objEscapeTag->hasContent($strOutputStream)) $strOutputStream = $objEscapeTag->restoreContent($strOutputStream);
		if($objScriptTag->hasContent($strOutputStream)) $strOutputStream = $objScriptTag->restoreContent($strOutputStream);
		if($objStyleTag->hasContent($strOutputStream)) 	$strOutputStream = $objStyleTag->restoreContent($strOutputStream);
		
		// save compilation
		$objCompilation->putContents($strOutputStream);
		
		return $this->strCompilationFile;
	}
}
