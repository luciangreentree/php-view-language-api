<?php
/**
 * Implements operations common to all non-parsable (system) tags. All system tag classes must extend it.
 */
abstract class AbstractNonParsableTag {
	protected $blnKeepTag;
	protected $strTagName;
	protected $tblMatches;
	
	/**
	 * Checks if tag has a body.
	 * 
	 * @param string $strOutputStream
	 * @return boolean
	 */
	public function hasContent($strOutputStream) {
		return (stripos($strOutputStream, "<".$this->strTagName.">")!==false || stripos($strOutputStream, "<".$this->strTagName." ")!==false?true:false);
	}
	
	/**
	 * Removes body from tag.
	 * 
	 * @param string $strSubject
	 * @return string
	 */
	public function removeContent($strSubject) {
		preg_match_all("/\<".$this->strTagName."(.*?)\>(.*?)\<\/".$this->strTagName."\>/si",$strSubject,$tblMatches,PREG_PATTERN_ORDER);
		foreach($tblMatches[0] as $intIndex=>$strValue) {
			$strSubject = str_replace($strValue, "<".$this->strTagName.">$intIndex</".$this->strTagName.">", $strSubject);
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
			if($this->blnKeepTag) {
				$strSubject = str_replace("<".$this->strTagName.">".$intIndex."</".$this->strTagName.">", "<".$this->strTagName." ".$this->tblMatches[1][$intIndex].">".$this->tblMatches[2][$intIndex]."</".$this->strTagName.">", $strSubject);
			} else {
				$strSubject = str_replace("<".$this->strTagName.">".$intIndex."</".$this->strTagName.">", $this->tblMatches[2][$intIndex], $strSubject);
			}
		}
		return $strSubject;
	}
}