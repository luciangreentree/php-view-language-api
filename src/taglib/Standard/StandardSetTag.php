<?php
/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <standard:set name="VARNAME" value="EXPRESSION"/>
*
* Tag example:
* <standard:set name="asd" value="16"/>
*
* PHP output:
* <?php $asd = "16"; ?>
*/
class StandardSetTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("name","value"));
		return '<?php $'.$tblParameters['name'].' = '.($this->isExpression($tblParameters['value'])?$this->parseExpression($tblParameters['value']):"'".addslashes($tblParameters['value'])."'").'; ?>';
	}
}