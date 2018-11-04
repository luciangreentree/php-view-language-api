<?php
namespace Lucinda\Templating;

/**
 * Implements how an ELSE clause is translated into a tag.
*
* Tag syntax:
* <:else>BODY
*/
class StdElseTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php } else { ?>';
	}
}