<?php
require_once("File.php");
require_once("ExpressionParser.php");
require_once("TagParser.php");
require_once("ViewCompilation.php");
require_once("taglib/Std/loader.php");
require_once("taglib/System/loader.php");

/**
 * Performs the logic of view language parsing, delegating to tag and expressions parser.
 */
class ViewLanguageParser {
	private $templatesFolder;
	private $compilationsFolder;
	private $templatesExtension;
	private $tagLibFolder;
	
	/**
	 * Constructs a view language parser.
	 * 
	 * @param string $templatesFolder Relative path to templates folder on disk.
	 * @param string $templatesExtension Extension of template files without dot (eg: php).
	 * @param string $compilationsFolder Absolute path to compilations folder on disk 
	 * @param string $tagLibFolder Relative path to user-defined tag libraries folder.
	 */
	public function __construct($templatesFolder, $templatesExtension, $compilationsFolder, $tagLibFolder = "") {
		$this->templatesFolder = $templatesFolder;
		$this->templatesExtension = $templatesExtension;
		$this->compilationsFolder= $compilationsFolder;
		$this->tagLibFolder = $tagLibFolder;
	}
	
	/**
	 * Compiles ViewLanguage instructions in input file/string into PHP, saves global view into a compilation file, then returns location to that file.
	 * 
	 * @param string $templatePath Relative path to template file that needs to be compiled within templates folder (without extension). 
	 * @param string $outputStream Response stream contents before view language constructs were parsed.
	 * @return string Compilation file name, containing response stream after view language constructs were parsed.
	 */
	public function compile($templatePath, $outputStream="") {
		// opens existing compilation (if exists)
		$viewCompilation = new ViewCompilation($this->compilationsFolder, $templatePath, $this->templatesExtension);
		
		// if compilation components haven't changed, do not go further
		if(!$viewCompilation->hasChanged()) {
			return $viewCompilation->getCompilationPath();
		}
		
		// includes dependant tree of templates
		$importTag = new SystemImportTag($this->templatesFolder, $this->templatesExtension, $viewCompilation);
		$outputStream = $importTag->parse($templatePath, $outputStream);
		
		// start looking for tags whose values should be escaped
		$escapeTag = new SystemEscapeTag();
		$hasEscapedContent = $escapeTag->hasContent($outputStream);
		
		// backup escaped content
		if($hasEscapedContent) $escapeTag->removeContent($outputStream);

		// run tag parser
		$tagParser = new TagParser($this->tagLibFolder, $this->templatesExtension, $viewCompilation);
		$outputStream=$tagParser->parse($outputStream);
		
		// run expression parser
		$expressionParser = new ExpressionParser();
		$outputStream=$expressionParser->parse($outputStream);
		
		// restore escaped content
		if($hasEscapedContent) $escapeTag->restoreContent($outputStream);
		
		// saves new compilation
		$viewCompilation->save($outputStream);
		
		return $viewCompilation->getCompilationPath();
	}
}
