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
class SystemEscapeTag extends AbstractNonParsableTag  {
	public function __construct() {
		$this->strTagName = "escape";
		$this->blnKeepTag = false;
	}
}