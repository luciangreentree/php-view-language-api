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
	 * {@inheritDoc}
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
	    $this->checkParameters($parameters, array("test"));
		return '<?php if ('.$this->parseExpression($parameters['test']).') { ?>';
	}

	/**
	 * {@inheritDoc}
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}