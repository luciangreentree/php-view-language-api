<?php
namespace Lucinda\Templating;
/**
 * Implements how an ELSE clause is translated into a tag.
*
* Tag syntax:
* <std:else>BODY</std:else>
*
* Tag example:
* <std:else>hello</std:else>
*
* PHP output:
* <?php else { ?>hello<?php } ?>
*/
class StdElseTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		return '<?php } else { ?>';
	}
}