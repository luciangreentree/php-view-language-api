<?php
/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <std:unset name="VARNAME"/>
*
* Tag example:
* <std:set name="asd" value="16"/>
*
* PHP output:
* <?php unset($asd); ?>
*/
class StdUnsetTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		if(!$this->checkParameters($tblParameters, array("name"))) {
			throw new ViewException("std:unset requires parameters: 'name'");
		}
		return '<?php unset($'.$tblParameters['name'].'); ?>';
	}
}