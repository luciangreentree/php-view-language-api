<?php
namespace Lucinda\Templating;

/**
 * Reads templating tag in order to bind it to a view and compiles response
 */
class Wrapper
{
    private $templatesFolder;
    private $compilationsFolder;
    private $templatesExtension;
    private $tagLibFolder;
    
    /**
     * Calls for interpreting contents of <application> XML tag.
     *
     * @param \SimpleXMLElement $xml XML file holding compiler settings.
     * @throws ConfigurationException If XML is improperly configured.
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->readConfiguration($xml);
    }
    
    /**
     * Reads XML then delegates to ViewLanguageAPI to compile a templated view recursively into a PHP file.
     *
     * @param \SimpleXMLElement $xml XML file holding compiler settings.
     * @throws ConfigurationException If XML is improperly configured.
     */
    private function readConfiguration(\SimpleXMLElement $xml): void
    {
        // parses XML
        $xml = $xml->templating;
        if (empty($xml)) {
            throw new ConfigurationException("Tag 'templating' missing");
        }
        
        // gets settings from attributes
        $this->compilationsFolder = (string) $xml["compilations_path"];
        if (!$this->compilationsFolder) {
            throw new ConfigurationException("Attribute 'compilations' of tag 'templating' is empty or missing");
        }        
        $this->tagLibFolder = (string) $xml["tags_path"];
        $this->templatesFolder = (string) $xml["templates_path"];
        $this->templatesExtension = (string) $xml["templates_extension"];
        if (!$this->templatesExtension) {
            $this->templatesExtension = "html";
        }
    }
    
    /**
     * Loads compilation file, binds it to data and returns HTML to be rendered
     *
     * @param string $viewFile View file location (without extension, optionally including views folder path)
     * @param array $data
     * @return string
     * @throws ViewException If compilation failed due to a developer error.
     */
    public function compile(string $viewFile, array $data): string
    {        
        // gets view file
        if ($this->templatesFolder && strpos($viewFile, $this->templatesFolder)===0) {
            $viewFile = substr($viewFile, strlen($this->templatesFolder)+1);
        }
        
        // compiles templates recursively into a single PHP file
        $vlp = new ViewLanguageParser($this->templatesFolder, $this->templatesExtension, $this->compilationsFolder, $this->tagLibFolder);
        $compilationFile = $vlp->compile($viewFile);
        
        // compiles PHP file into HTML
        ob_start();
        require($compilationFile);
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
}
