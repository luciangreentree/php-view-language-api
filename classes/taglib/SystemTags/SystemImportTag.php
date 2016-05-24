<?php
/**
 * Implements a tag whose only attribute value points to a PHP (template) file whose sources are loaded. 
 * 
 * Tag syntax:
 * <import file="FILEPATH"/>
 * 
 * Tag example:
 * <import file="temp/users"/>
 *
 * PHP output:
 * file_get_contents(VIEWS_PATH."/"."temp/users".".php");
 */
class SystemImportTag {	
	private $intModificationTime;
	private $strTemplatePath;
	
	/**
	 * Sets up path in which template are looked after and the time of modification for page-specific view file.
	 * 
	 * @param string $strTemplatePath
	 * @param integer $intViewModificationTime
	 */
	public function __construct($strTemplatePath, $intViewModificationTime) {
		$this->strTemplatePath = $strTemplatePath;
		$this->intModificationTime = $intViewModificationTime;
	}
	
	/**
	 * Parses template source file for import tags recursively. For each template file loaded, modification time is adjusted to confirm to the latest.
	 * 
	 * @param string $strSubject
	 * @throws ViewException
	 * @return string
	 */
	public function parse($strSubject) {
		return preg_replace_callback("/<import\ file\=\"(.*?)\"\/\>/", function($tblMatches) {
			$strFilePath = VIEWS_PATH.'/'.$tblMatches[1].'.php';
			if(!file_exists($strFilePath)) throw new ViewException("Template not found: ".$strFilePath);
			$intModificationTime = filemtime($strFilePath);
			if($intModificationTime>$this->intModificationTime) $this->intModificationTime = $intModificationTime;
			$strContents = file_get_contents($strFilePath);
			return $this->parse($strContents);
		},$strSubject);
	} 
	
	/**
	 * Gets templates used latest modification time.
	 * 
	 * @return integer
	 */
	public function getModifiedTime() {
		return $this->intModificationTime;
	}
}