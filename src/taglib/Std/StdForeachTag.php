<?php
/**
 * Implements how a FOREACH clause is translated into a tag.
*
* Tag syntax:
* <std:foreach var="EXPRESSION" key="KEYNAME" value="VALUENAME">BODY</std:foreach>
*
* Tag example:
* <std:foreach var="${a.b}" key="${keyName}" value="${valueName}">BODY</std:foreach>
*
* PHP output:
* <?php foreach($a["b"] as $keyName=>$valueName) { ?> BODY <?php } ?>
*/
class StdForeachTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		if(!$this->checkParameters($tblParameters, array("var","value")) || !$this->isExpression($tblParameters['var'])) {
			return '<?php foreach([] as $empty) { ?>';
		} else {
			return '<?php foreach('.$this->parseExpression($tblParameters['var']).' as '.(!empty($tblParameters['key'])?'$'.$tblParameters['key'].'=>':'').'$'.$tblParameters['value'].') { ?>';
		}
		
	}

	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}