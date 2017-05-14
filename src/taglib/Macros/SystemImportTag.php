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
	private $objViewCompilation;
	private $strTemplatesFolder;
	private $strTemplatesExtension;
	
	/**
	 * Sets up path in which template are looked after and the time of modification for page-specific view file.
	 * 
	 * @param string $strTemplatesFolder
	 * @param string $strTemplatesExtension
	 * @param ViewCompilation $objViewCompilation
	 */
	public function __construct($strTemplatesFolder, $strTemplatesExtension, ViewCompilation $objViewCompilation) {
		$this->strTemplatesFolder = $strTemplatesFolder;
		$this->strTemplatesExtension = $strTemplatesExtension;
		$this->objViewCompilation = $objViewCompilation;
	}
	
	/**
	 * Parses template source file for import tags recursively. For each template file loaded, modification time is adjusted to confirm to the latest.
	 * 
	 * @param string $strSubject
	 * @param string $strOutputStream
	 * @throws ViewException
	 * @return string
	 */
	public function parse($strTemplateFile, $strOutputStream="") {
		$strPath = $this->strTemplatesFolder."/".$strTemplateFile.".".$this->strTemplatesExtension;
	    $file = new File($strPath);
	    $strSubject = ($strOutputStream==""?$file->getContents():$strOutputStream);
	    $this->objViewCompilation->addComponent($strPath);
	    
		return preg_replace_callback("/<import\ file\=\"(.*?)\"\/\>/", function($tblMatches) {
			return $this->parse($tblMatches[1]);
		},$strSubject);
	} 
}
