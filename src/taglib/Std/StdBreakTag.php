<?php
namespace Lucinda\Templating;

/**
 * Implements a BREAK operation in a loop.
 *
 * Tag syntax:
 * <:break/>
 */
class StdBreakTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php break; ?>';
	}
}