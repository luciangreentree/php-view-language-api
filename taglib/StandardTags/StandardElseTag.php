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
class StandardElseTag extends AbstractParsableTag {
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		return '<?php else { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}