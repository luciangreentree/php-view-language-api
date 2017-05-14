<?php
/**
 * Implements how an IF clause is translated into a tag.
*
* Tag syntax:
* <std:if condition="EXPRESSION">BODY</std:if>
*
* Tag example:
* <std:if condition="${a}>2">BODY</std:if>
*
* PHP output:
* <?php if ($a>2) { ?> BODY <?php } ?>
*/
class StdIfTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("condition"));
		return '<?php if ('.$this->parseExpression($tblParameters['condition']).') { ?>';
	}

	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}