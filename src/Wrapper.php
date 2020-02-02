<?php
namespace Lucinda\Templating;

/**
 * Reads templating tag in order to bind it to a view and compiles response
 */
class Wrapper
{
    private $compilationFile;
    
    /**
     * Compiles view file from ViewLanguage to PHP using also contents of <application> XML tag.
     *
     * @param \SimpleXMLElement $xml XML file holding compiler settings.
     * @param string $viewFile View file location (without extension, optionally including views folder path)
     * @throws ConfigurationException If XML is improperly configured.
     * @throws ViewException If compilation failed due to a developer error.
     */
    public function __construct(\SimpleXMLElement $xml, string $viewFile)
    {
        $this->setCompilationFile($xml, $viewFile);
    }
    
    /**
     * Reads XML then delegates to ViewLanguageAPI to compile a templated view recursively into a PHP file.
     *
     * @param \SimpleXMLElement $xml XML file holding compiler settings.
     * @param string $viewFile View file location (without extension, optionally including views folder path)
     * @throws ConfigurationException If XML is improperly configured.
     * @throws ViewException If compilation failed due to a developer error.
     */
    private function setCompilationFile(\SimpleXMLElement $xml, string $viewFile): void
    {
        // parses XML
        $xml = $xml->templating;
        if (empty($xml)) {
            throw new ConfigurationException("Tag 'templating' missing");
        }
        
        // get settings necessary in compilation
        $compilationsFolder = (string) $xml["compilations_path"];
        if (!$compilationsFolder) {
            throw new ConfigurationException("Tag 'compilations' child of 'paths' child of 'application' tags is empty or missing");
        }
        $tagsFolder = (string) $xml["tags_path"];
        $viewsFolder = (string) $xml["templates_path"];
        $extension = (string) $xml["templates_extension"];
        if (!$extension) {
            $extension = "html";
        }
        
        // gets view file
        if ($viewsFolder && strpos($viewFile, $viewsFolder)===0) {
            $viewFile = substr($viewFile, strlen($viewsFolder)+1);
        }
        
        // compiles templates recursively into a single compilation file
        $vlp = new ViewLanguageParser($viewsFolder, $extension, $compilationsFolder, $tagsFolder);
        $this->compilationFile = $vlp->compile($viewFile);
    }
    
    /**
     * Loads compilation file, binds it to data and returns HTML to be rendered
     * 
     * @param array $data
     * @return string
     */
    public function compile(array $data): string
    {
        ob_start();
        require($this->compilationFile);
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
}
