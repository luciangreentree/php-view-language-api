<?php
/**
 * Implements how setting an internal variable is translated into a tag.
 *
 * Tag syntax:
 * <standard:unset name="VARNAME"/>
 *
 * Tag example:
 * <standard:set name="asd" value="16"/>
 *
 * PHP output:
 * <?php unset($asd); ?>
 */
class StandardUnsetTag extends AbstractParsableTag {
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("name"));
		return '<?php unset($'.$tblParameters['name'].'); ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '';
	}
}