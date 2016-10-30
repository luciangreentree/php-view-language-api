<?php
/**
 * Skips parsing PHP tags <?php ... ?>.
 */
class SystemPHPTag extends AbstractNonParsableTag {
	/**
	 * {@inheritDoc}
	 * @see AbstractNonParsableTag::hasContent()
	 */
	public function hasContent($strSubject) {
		return ((strpos($strSubject, "<? ")!==false || strpos($strSubject, "<?php ")!==false)?true:false);
	}
	
	/**
	 * {@inheritDoc}
	 * @see AbstractNonParsableTag::removeContent()
	 */
	public function removeContent(&$strSubject) {
		$strSubject = str_replace("<? ","<?php ", $strSubject);
		preg_match_all("/\<\?php (.*?) \?\>/si",$strSubject,$tblMatches,PREG_PATTERN_ORDER);
		foreach($tblMatches[0] as $intIndex=>$strValue) {
			$strSubject = str_replace($strValue, "<?php ".$intIndex." ?>", $strSubject);
		}
		$this->tblMatches = $tblMatches;
	}

	/**
	 * {@inheritDoc}
	 * @see AbstractNonParsableTag::restoreContent()
	 */
	public function restoreContent(&$strSubject) {
		foreach($this->tblMatches[0] as $intIndex=>$strValue) {
			$strSubject = str_replace("<?php ".$intIndex." ?>", "<?php ".$this->tblMatches[1][$intIndex]." ?>", $strSubject);
		}
	}
}