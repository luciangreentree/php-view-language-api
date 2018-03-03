<?php
/**
 * Implements a tag whose body is not parsable (into other tags or expressions).
*
* Tag syntax:
* <escape>BODY</escape>
*
* Tag example:
* <escape>hello, asd${b}</escape>
*
* PHP output:
* hello, asd${b}
*/
class SystemEscapeTag {
	protected $keepTag;
	protected $tagName;
	protected $matches;

	public function __construct() {
		$this->tagName = "escape";
		$this->keepTag = false;
	}

	/**
	 * Checks if tag has a body.
	 *
	 * @param string $outputStream
	 * @return boolean
	 */
	public function hasContent($subject) {
		return (stripos($subject, "<".$this->tagName.">")!==false || stripos($subject, "<".$this->tagName." ")!==false?true:false);
	}

	/**
	 * Removes body from tag.
	 *
	 * @param string $subject
	 * @return string
	 */
	public function removeContent(&$subject) {
		preg_match_all("/\<".$this->tagName."(.*?)\>(.*?)\<\/".$this->tagName."\>/si",$subject,$matches,PREG_PATTERN_ORDER);
		foreach($matches[0] as $index=>$value) {
			$subject = str_replace($value, "<".$this->tagName.">$index</".$this->tagName.">", $subject);
		}
		$this->matches = $matches;
	}

	/**
	 * Restores body from tag.
	 *
	 * @param string $subject
	 * @return string
	 */
	public function restoreContent(&$subject) {
		foreach($this->matches[0] as $index=>$value) {
			if($this->keepTag) {
				$subject = str_replace("<".$this->tagName.">".$index."</".$this->tagName.">", "<".$this->tagName." ".$this->matches[1][$index].">".$this->matches[2][$index]."</".$this->tagName.">", $subject);
			} else {
				$subject = str_replace("<".$this->tagName.">".$index."</".$this->tagName.">", $this->matches[2][$index], $subject);
			}
		}
	}
}