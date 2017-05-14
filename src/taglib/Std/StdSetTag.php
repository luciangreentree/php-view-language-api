<?php
/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <std:set name="VARNAME" value="EXPRESSION"/>
*
* Tag example:
* <std:set name="asd" value="16"/>
*
* PHP output:
* <?php $asd = "16"; ?>
*/
class StdSetTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("name","value"));
		return '<?php $'.$tblParameters['name'].' = '.($this->isExpression($tblParameters['value'])?$this->parseExpression($tblParameters['value']):"'".addslashes($tblParameters['value'])."'").'; ?>';
	}
}