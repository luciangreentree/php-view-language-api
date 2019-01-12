<?php
namespace Lucinda\Templating;

/**
 * Implements how an ELSE IF clause is translated into a tag.
*
* Tag syntax:
* <:elseif test="EXPRESSION">BODY
*/
class StdElseifTag extends SystemTag implements StartTag {
	/**
	 * {@inheritDoc}
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		$this->checkParameters($parameters, array("test"));
		return '<?php } else if ('.$this->parseExpression($parameters['test']).') { ?>';
	}
}