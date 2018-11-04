<?php
namespace Lucinda\Templating;

/**
* Implements how a FOREACH clause is translated into a tag.
*
* Tag syntax:
* <:foreach var="EXPRESSION" key="KEYNAME" val="VALUENAME">BODY</:foreach>
*/
class StdForeachTag extends SystemTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("var","val")) || !$this->isExpression($parameters['var'])) {
			return '<?php foreach([] as $empty) { ?>';
		} else {
			return '<?php foreach('.$this->parseExpression($parameters['var']).' as '.(!empty($parameters['key'])?'$'.$parameters['key'].'=>':'').'$'.$parameters['val'].') { ?>';
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