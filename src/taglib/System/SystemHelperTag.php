<?php
namespace Lucinda\Templating;

/**
 * Implements a tag that loads a view "helper" file holding an user-defined collection of procedural php functions callable in templates,
 * complementing PHP's own embedded view helper functions (eg: striptags).
 *
 * Tag syntax:
 * <:helper file="FILEPATH"/>
 */
class SystemHelperTag {    
    /**
     * Parses output stream for helper tags. If found, loads files referenced then removes tag declaration.
     * 
     * @param string $outputStream Output stream possibly containing <:helper ...> tag declarations.
     * @throws ViewException If helper file could not be located on disk.
     * @return string Output stream without <:helper ...> tag declarations.
     */
    public function parse($outputStream) {
        return preg_replace_callback("/<:helper\s+file\s*\=\s*\"(.*?)\"\s*\/?\>/", function($matches) {
            $file = $matches[1].".php";
            if(!file_exists($file)) throw new ViewException("Helper file not found: ". $file);
            require_once($file);
            return "";
        },$outputStream);
    }
}