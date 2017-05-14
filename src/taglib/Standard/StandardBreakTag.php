<?php
/**
 * Implements a BREAK operation in a loop.
 *
 * Tag syntax:
 * <standard:break/>
 *
 * PHP output:
 * <?php break; ?>
 */
class StandardBreakTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		return '<?php break; ?>';
	}
}