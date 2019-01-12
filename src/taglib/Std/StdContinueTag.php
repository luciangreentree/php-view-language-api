<?php
namespace Lucinda\Templating;

/**
 * Implements a CONTINUE operation in a loop.
 *
 * Tag syntax:
 * <:continue/>
 */
class StdContinueTag extends SystemTag implements StartTag {
	/**
	 * {@inheritDoc}
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php continue; ?>';
	}
}