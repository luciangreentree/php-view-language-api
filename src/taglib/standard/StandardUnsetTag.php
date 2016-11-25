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
class StandardUnsetTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("name"));
		return '<?php unset($'.$tblParameters['name'].'); ?>';
	}
}