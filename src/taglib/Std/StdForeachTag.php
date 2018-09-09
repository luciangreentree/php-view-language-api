<?php
namespace Lucinda\Templating;
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
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("var","value")) || !$this->isExpression($parameters['var'])) {
			return '<?php foreach([] as $empty) { ?>';
		} else {
			return '<?php foreach('.$this->parseExpression($parameters['var']).' as '.(!empty($parameters['key'])?'$'.$parameters['key'].'=>':'').'$'.$parameters['value'].') { ?>';
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