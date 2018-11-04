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
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("test"))) {
			return '<?php } else if (false) { ?>';
		} else {
			return '<?php } else if ('.$this->parseExpression($parameters['test']).') { ?>';
		}		
	}
}