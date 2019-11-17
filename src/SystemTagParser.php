<?php
namespace Lucinda\Templating;

require("SystemTag.php");

/**
 * Parses system tags and appends them to compilation
 */
class SystemTagParser
{
    private $attributesParser;
    
    /**
     * Constructor instancing attributes parser
     */
    public function __construct()
    {
        $this->attributesParser = new AttributesParser();
    }
    
    /**
     * Looks for tags in views and returns an answer where each found match is converted to PHP.
     *
     * @param string $subject
     * @param SystemEscapeTag $escaper
     * @return string
     */
    public function parse($subject)
    {
        // match start & end tags
        $subject = preg_replace_callback("/<:([a-z]+)\s*((.*)\s*=\s*\"(.*)\"\s*)?\/?>/", function ($matches) {
            return $this->getTagInstance($matches)->parseStartTag(isset($matches[2])?$this->attributesParser->parse($matches[2]):array());
        }, $subject);
        $subject = preg_replace_callback("/<\/:([a-z]+)>/", function ($matches) {
            return $this->getTagInstance($matches)->parseEndTag();
        }, $subject);
        return $subject;
    }
    
    /**
     * Detects tag class from tag declaration.
     *
     * @param array $matches
     * @throws ViewException
     * @return StartEndTag
     */
    private function getTagInstance($matches)
    {
        $className = "Std".ucwords($matches[1])."Tag";
        $fileLocation = __DIR__."/taglib/Std/".$className.".php";
        if (!file_exists($fileLocation)) {
            throw new ViewException("System tag not found: ".$matches[1]);
        }
        require_once($fileLocation);
        $className = __NAMESPACE__."\\".$className;
        return new $className();
    }
}
