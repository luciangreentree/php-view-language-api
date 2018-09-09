<?php
namespace Lucinda\Templating;
/**
 * Implements a BREAK operation in a loop.
 *
 * Tag syntax:
 * <std:break/>
 *
 * PHP output:
 * <?php break; ?>
 */
class StdBreakTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php break; ?>';
	}
}