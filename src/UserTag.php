<?php
namespace Lucinda\Templating;
/**
 * Defines a generic class to parse all user-defined procedural tags.
 */
class UserTag implements StartTag {
	private $filePath;
	
	/**
	 * @param string $filePath Location of tag procedural file.
	 */
	public function __construct($filePath) {
		$this->filePath = $filePath;
	}
	
	/**
	 * {@inheritDoc}
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		$content= file_get_contents($this->filePath);
		return preg_replace_callback("/[\$]\[([a-zA-Z0-9\-_.]+)\]/",function($match) use($parameters){
			return (isset($parameters[$match[1]])?$parameters[$match[1]]:null);
		},$content);
	}
}