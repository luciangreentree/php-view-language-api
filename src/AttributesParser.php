<?php
namespace Lucinda\Templating;

/**
 * Class performing regex parsing of tags for attributes.
 */
class AttributesParser
{
    private $required = array();
    
    /**
     * Constructs parser from required attributes.
     *
     * @param string[] $required Required attributes for tag.
     */
    public function __construct($required=array())
    {
        $this->required = $required;
    }
    
    /**
     * Parses string for tag attributes via regex
     *
     * @param string $parameters
     * @throws ViewException If string doesn't included attributes required by tag
     * @return string[string] Attributes by name and value.
     */
    public function parse($parameters)
    {
        if (!$parameters || $parameters=="/") {
            if (empty($this->required)) {
                return array();
            } else {
                $trace = debug_backtrace();
                preg_match("/([a-zA-Z]+)\/([a-zA-Z]+)Tag.php$/", $trace[0]["file"], $matches);
                $tagName = ($matches[1]=="Std"?":":"").strtolower(str_replace($matches[1], "", $matches[2]));
                throw new ViewException("Tag '".$tagName."' requires attributes: ".implode(", ", $this->required));
            }
        }
        preg_match_all('/([a-zA-Z0-9\-_.]+)\s*=\s*"\s*([^"]+)\s*"/', $parameters, $tmp, PREG_SET_ORDER);
        $output=array();
        foreach ($tmp as $values) {
            $output[$values[1]]=$values[2];
        }
        foreach ($this->required as $attributeName) {
            if (!isset($output[$attributeName])) {
                $trace = debug_backtrace();
                preg_match("/([a-zA-Z]+)\/([a-zA-Z]+)Tag.php$/", $trace[0]["file"], $matches);
                $tagName = ($matches[1]=="Std"?":":"").strtolower(str_replace($matches[1], "", $matches[2]));
                throw new ViewException("Tag '".$tagName."' requires attribute: ".$attributeName);
            }
        }
        return $output;
    }
}
