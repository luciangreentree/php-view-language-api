<?php
/**
 * Implements a CONTINUE operation in a loop.
 *
 * Tag syntax:
 * <std:continue/>
 *
 * PHP output:
 * <?php continue; ?>
 */
class StdContinueTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php continue; ?>';
	}
}