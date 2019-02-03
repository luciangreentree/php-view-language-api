<?php
namespace Lucinda\Templating;

require_once("StartTag.php");

/**
 * Defines a generic class to parse all user-defined procedural tags.
 */
class UserTag implements StartTag {
	private $filePath;
	
	/**
     * Constructs parser based on user-defined tag location.
     * 
	 * @param string $filePath Location of tag procedural file.
	 */
	public function __construct($filePath) {
		$this->filePath = $filePath;
	}
	
	/**
	 * Parses start tag.
	 *
	 * @param array(string=>string) $parameters
	 * @return string
	 */
	public function parseStartTag($parameters=array()) {
		$content= file_get_contents($this->filePath);
		return preg_replace_callback("/[\$]\[([a-zA-Z0-9\-_.]+)\]/",function($match) use($parameters){
			return (isset($parameters[$match[1]])?$parameters[$match[1]]:null);
		},$content);
	}
}