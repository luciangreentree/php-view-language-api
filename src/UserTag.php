<?php
/**
 * Defines a generic class to parse all user-defined procedural tags under following rules:
 * - start and end tag bodies are separated using: <separator/>
 * - start tag (mandatory) body will be evaluated
 * - end tag (optional) body will be taken as text (not evaluated)
 */
class UserTag implements StartEndTag {
	const BODY_TAG = "<separator/>";
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
		ob_start();
		include $this->strFilePath;
		$strOutput = ob_get_contents();
		$position = strpos($strOutput,self::BODY_TAG);
		if($position!==false) {
			$strOutput = substr($strOutput, 0, $position);
		}
		ob_end_clean();	
		return $strOutput;
	}
	
	/**
	 * {@inheritDoc}
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		$strContent = file_get_contents($this->strFilePath);
		$position = strpos($strContent,self::BODY_TAG);
		if($position!==false) {
			return substr($strContent, $position+strlen(self::BODY_TAG));
		} else {
			return "";
		}
	}
}