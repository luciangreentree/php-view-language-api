<?php
/**
 * Skips parsing tags or expressions in HTML-standard <style ...> tags. 
 * ATTENTION: In nearly all cases, hardcoding CSS into template files is bad design.
 *
 * Tag syntax:
 * <style>CSS</style>
 *
 * Tag example:
 * <style>body{color:black;}</style>
 *
 * PHP output:
 * <style>body{color:black;}</style>
 */
class SystemStyleTag extends AbstractNonParsableTag  {
	public function __construct() {
		$this->strTagName = "style";
		$this->blnKeepTag = true;
	}
}