<?php
/**
 * Implements how an ELSE clause is translated into a tag.
*
* Tag syntax:
* <standard:else>BODY</standard:else>
*
* Tag example:
* <standard:else>hello</standard:else>
*
* PHP output:
* <?php else { ?>hello<?php } ?>
*/
class StandardElseTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		return '<?php } else { ?>';
	}
}