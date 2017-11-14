<?php
/**
 * Defines a generic class to parse all user-defined procedural tags.
 */
class UserTag implements StartTag {
	private $strFilePath;
	
	/**
	 * @param string $strFilePath Location of tag procedural file.
	 */
	public function __construct($strFilePath) {
		$this->strFilePath = $strFilePath;
	}
	
	/**
	 * {@inheritDoc}
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		$strContent= file_get_contents($this->strFilePath);
		return preg_replace_callback("/[\$]\[([a-zA-Z]+)\]/",function($match) use($parameters){
			return (isset($parameters[$match[1]])?$parameters[$match[1]]:null);
		},$strContent);
	}
}