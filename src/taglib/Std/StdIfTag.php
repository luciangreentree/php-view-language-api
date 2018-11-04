<?php
namespace Lucinda\Templating;

/**
 * Implements how an IF clause is translated into a tag.
*
* Tag syntax:
* <:if test="EXPRESSION">BODY</:if>
*/
class StdIfTag extends SystemTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("test"))) {
			return '<?php if(false) { ?>';
		} else {
			return '<?php if ('.$this->parseExpression($parameters['test']).') { ?>';
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