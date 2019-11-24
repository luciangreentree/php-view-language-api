<?php
namespace Lucinda\Templating\TagLib\System;

use Lucinda\Templating\ViewCompilation;
use Lucinda\Templating\File;

/**
 * Implements a tag whose only attribute value points to a PHP (template) file whose sources are loaded.
 *
 * Tag syntax:
 * <import file="FILEPATH"/>
 */
class ImportTag
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
    public function __construct(string $templatesFolder, string $templatesExtension, ViewCompilation $viewCompilation): void
    {
        $this->templatesFolder = $templatesFolder;
        $this->templatesExtension = $templatesExtension;
        $this->viewCompilation = $viewCompilation;
    }
    
    /**
     * Parses template source file for import tags recursively. For each template file loaded, modification time is adjusted to confirm to the latest.
     *
     * @param string $templateFile
     * @param EscapeTag $escaper
     * @param string $outputStream
     * @return string
     */
    public function parse(string $templateFile, EscapeTag $escaper, string $outputStream=""): string
    {
        $path = ($this->templatesFolder?$this->templatesFolder."/":"").$templateFile.".".$this->templatesExtension;
        $file = new File($path);
        $subject = ($outputStream==""?$file->getContents():$outputStream);
        $subject = $escaper->backup($subject);
        $this->viewCompilation->addComponent($path);
        
        return preg_replace_callback("/<import\s+file\s*\=\s*\"(.*?)\"\s*\/\>/", function ($matches) use ($escaper) {
            return $this->parse($matches[1], $escaper);
        }, $subject);
    }
}
