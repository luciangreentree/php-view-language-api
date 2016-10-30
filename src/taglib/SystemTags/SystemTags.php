<?php
require_once("SystemEscapeTag.php");
require_once("SystemImportTag.php");
require_once("SystemScriptTag.php");
require_once("SystemStyleTag.php");
require_once("SystemPHPTag.php");

/**
 * Envelopes system tags into a backup-restore paradigm
 */
class SystemTags {
	private $objEscapeTag;
	private $objScriptTag;
	private $objStyleTag;
	private $objPHPTag;
	
	public function __construct() {
		$this->objEscapeTag = new SystemEscapeTag();
		$this->objScriptTag = new SystemScriptTag();
		$this->objStyleTag = new SystemStyleTag();
		$this->objPHPTag = new SystemPHPTag();
	}
	
	/**
	 * Backs-up system tags to prevent being parsed for tags/expressions.
	 * 
	 * @param string $strOutputStream
	 */
	public function backup(&$strOutputStream) {
		if($this->objEscapeTag->hasContent($strOutputStream)) $this->objEscapeTag->removeContent($strOutputStream);
		if($this->objScriptTag->hasContent($strOutputStream)) $this->objScriptTag->removeContent($strOutputStream);
		if($this->objStyleTag->hasContent($strOutputStream))  $this->objStyleTag->removeContent($strOutputStream);
		if($this->objPHPTag->hasContent($strOutputStream)) 	  $this->objPHPTag->removeContent($strOutputStream);
	}
	
	/**
	 * Restores system tags.
	 * 
	 * @param string $strOutputStream
	 */
	public function restore(&$strOutputStream){
		if($this->objEscapeTag->hasContent($strOutputStream)) $this->objEscapeTag->restoreContent($strOutputStream);
		if($this->objScriptTag->hasContent($strOutputStream)) $this->objScriptTag->restoreContent($strOutputStream);
		if($this->objStyleTag->hasContent($strOutputStream))  $this->objStyleTag->restoreContent($strOutputStream);
		if($this->objPHPTag->hasContent($strOutputStream)) 	  $this->objPHPTag->restoreContent($strOutputStream);
	}
}