<?php
/**
 * Implements how an ELSE IF clause is translated into a tag.
*
* Tag syntax:
* <standard:elseif condition="EXPRESSION">BODY</standard:elseif>
*
* Tag example:
* <standard:elseif condition="${a.b}>2">BODY</standard:elseif>
*
* PHP output:
* <?php else if ($a["b"]>2) { ?> BODY <?php } ?>
*/
class StandardElseifTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("condition"));
		return '<?php } else if ('.$this->parseExpression($tblParameters['condition']).') { ?>';
	}
}