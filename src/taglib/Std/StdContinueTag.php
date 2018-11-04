<?php
namespace Lucinda\Templating;

/**
 * Implements a CONTINUE operation in a loop.
 *
 * Tag syntax:
 * <:continue/>
 *
 * PHP output:
 * <?php continue; ?>
 */
class StdContinueTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php continue; ?>';
	}
}