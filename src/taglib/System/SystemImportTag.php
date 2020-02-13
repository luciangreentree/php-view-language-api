<?php
namespace Lucinda\Templating;

/**
 * Implements a tag whose only attribute value points to a PHP (template) file whose sources are loaded.
 *
 * Tag syntax:
 * <import file="FILEPATH"/>
 */
class SystemImportTag
{
    private $viewCompilation;
    private $templatesFolder;
    private $templatesExtension;
    
    /**
     * Sets up path in which template are looked after and the time of modification for page-specific view file.
     *
     * @param string $templatesFolder
     * @param string $templatesExtension
     * @param ViewCompilation $viewCompilation
     */
    public function __construct($templatesFolder, $templatesExtension, ViewCompilation $viewCompilation)
    {
        $this->templatesFolder = $templatesFolder;
        $this->templatesExtension = $templatesExtension;
        $this->viewCompilation = $viewCompilation;
    }
    
    /**
     * Parses template source file for import tags recursively. For each template file loaded, modification time is adjusted to confirm to the latest.
     *
     * @param string $templateFile
     * @param SystemEscapeTag $escaper
     * @param string $outputStream
     * @return string
     */
    public function parse($templateFile, SystemEscapeTag $escaper, $outputStream="")
    {
        $path = ($this->templatesFolder?$this->templatesFolder."/":"").$templateFile.".".$this->templatesExtension;
        $file = new File($path);
        if(!$file->exists()) {
            throw new ViewException("Invalid value of 'file' attribute @ 'import' tag: ".$templateFile);
        }
        $subject = ($outputStream==""?$file->getContents():$outputStream);
        $subject = $escaper->backup($subject);
        $this->viewCompilation->addComponent($path);
        
        return preg_replace_callback("/<import\s*(file\s*\=\s*\"(.*?)\")?\s*\/?>/", function ($matches) use ($escaper) {
            if (empty($matches[2])) {
                throw new ViewException("Tag 'import' requires attribute: file");
            }
            return $this->parse($matches[2], $escaper);
        }, $subject);
    }
}
