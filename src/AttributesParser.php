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
    public function __construct(array $required=array())
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
    public function parse(string $parameters): array
    {
        if (!$parameters || $parameters=="/") {
            if (empty($this->required)) {
                return array();
            } else {
                throw new ViewException("Tag '".$this->getTagName()."' requires attributes: ".implode(", ", $this->required));
            }
        }
        $tmp = [];
        preg_match_all('/([a-zA-Z0-9\-_.]+)\s*=\s*"\s*([^"]+)\s*"/', $parameters, $tmp, PREG_SET_ORDER);
        $output=array();
        foreach ($tmp as $values) {
            $output[$values[1]]=$values[2];
        }
        foreach ($this->required as $attributeName) {
            if (!isset($output[$attributeName])) {
                throw new ViewException("Tag '".$this->getTagName()."' requires attribute: ".$attributeName);
            }
        }
        return $output;
    }
    
    /**
     * Gets current tag name
     * 
     * @return string
     */
    private function getTagName(): string
    {
        $matches = [];
        $trace = debug_backtrace();
        preg_match("/([a-zA-Z]+)\/([a-zA-Z]+)Tag.php$/", $trace[1]["file"], $matches);
        return ($matches[1]=="Std"?":":"").strtolower($matches[2]);
    }
}
