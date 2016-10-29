<?php
class SystemPHPTag {
	protected $tblMatches;
	
	/**
	 * Checks if tag has a body.
	 * 
	 * @param string $strOutputStream
	 * @return boolean
	 */
	public function hasContent($strOutputStream) {
		return ((strpos($strOutputStream, "<? ")!==false || strpos($strOutputStream, "<?php ")!==false)?true:false);
	}
	
	/**
	 * Removes body from tag.
	 * 
	 * @param string $strSubject
	 * @return string
	 */
	public function removeContent($strSubject) {
		$strSubject = str_replace("<? ","<?php ", $strSubject);
		preg_match_all("/\<\?php (.*?) \?\>/si",$strSubject,$tblMatches,PREG_PATTERN_ORDER);
		foreach($tblMatches[0] as $intIndex=>$strValue) {
			$strSubject = str_replace($strValue, "<?php ".$intIndex." ?>", $strSubject);
		}
		$this->tblMatches = $tblMatches;
		return $strSubject;
	}
	
	/**
	 * Restores body from tag.
	 * 
	 * @param string $strSubject
	 * @return string
	 */
	public function restoreContent($strSubject) {
		foreach($this->tblMatches[0] as $intIndex=>$strValue) {
			$strSubject = str_replace("<?php ".$intIndex." ?>", "<?php ".$this->tblMatches[1][$intIndex]." ?>", $strSubject);
		}
		return $strSubject;
	}
}