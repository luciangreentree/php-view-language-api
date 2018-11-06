<?php
namespace Lucinda\Templating;
require_once("ViewCompilation.php");
require_once("AttributesParser.php");
require_once("taglib/System/loader.php");
require_once("UserTagParser.php");
require_once("SystemTagParser.php");
require_once("ExpressionParser.php");

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
     * @throws ViewException If compilation fails
     */
    public function compile($templatePath, $outputStream="") {
        // opens existing compilation (if exists)
        $viewCompilation = new ViewCompilation($this->compilationsFolder, $templatePath, $this->templatesExtension);
        
        // if compilation components haven't changed, do not go further
        if(!$viewCompilation->hasChanged()) {
            return $viewCompilation->getCompilationPath();
        }
        
        // instantiate template escaping logic
        $escapeTag = new SystemEscapeTag();
        
        // includes dependant tree of templates
        $importTag = new SystemImportTag($this->templatesFolder, $this->templatesExtension, $viewCompilation);
        $outputStream = $importTag->parse($templatePath, $escapeTag, $outputStream);
        
        $namespaceTag = new SystemNamespaceTag($this->tagLibFolder);
        $outputStream = $namespaceTag->parse($outputStream);
        
        // run user tag parser
        $userTagParser = new UserTagParser($namespaceTag, $this->templatesExtension, $viewCompilation);
        $outputStream = $userTagParser->parse($outputStream, $escapeTag);

        // run system tag parser
        $systemTagParser = new SystemTagParser();
        $outputStream = $systemTagParser->parse($outputStream);
                
        // run expression parser
        $expressionParser = new ExpressionParser();
        $outputStream = $expressionParser->parse($outputStream);
        
        // restore escaped content
        $outputStream = $escapeTag->restore($outputStream);
        
        // saves new compilation
        $viewCompilation->save($outputStream);
        
        return $viewCompilation->getCompilationPath();
    }
}
