<?php
namespace Lucinda\Templating;

/**
 * Implements how an IF clause is translated into a tag.
*
* Tag syntax:
* <:if condition="EXPRESSION">BODY</:if>
*
* Tag example:
* <:if condition="${a}>2">BODY</:if>
*
* PHP output:
* <?php if ($a>2) { ?> BODY <?php } ?>
*/
class StdIfTag extends SystemTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("condition"))) {
			return '<?php if(false) { ?>';
		} else {
			return '<?php if ('.$this->parseExpression($parameters['condition']).') { ?>';
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