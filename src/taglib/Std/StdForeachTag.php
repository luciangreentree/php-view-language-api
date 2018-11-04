<?php
namespace Lucinda\Templating;

/**
 * Implements how a FOREACH clause is translated into a tag.
*
* Tag syntax:
* <:foreach var="EXPRESSION" key="KEYNAME" value="VALUENAME">BODY</:foreach>
*
* Tag example:
* <:foreach var="${a.b}" key="${keyName}" value="${valueName}">BODY</:foreach>
*
* PHP output:
* <?php foreach($a["b"] as $keyName=>$valueName) { ?> BODY <?php } ?>
*/
class StdForeachTag extends SystemTag implements StartEndTag {
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