<?php
/**
 * Implements a CONTINUE operation in a loop.
 *
 * Tag syntax:
 * <standard:continue/>
 *
 * PHP output:
 * <?php continue; ?>
 */
class StandardContinueTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		return '<?php continue; ?>';
	}
}