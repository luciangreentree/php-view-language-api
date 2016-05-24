<?php
/**
 * Implements how an IF clause is translated into a tag.
 * 
 * Tag syntax:
 * <standard:if condition="EXPRESSION">BODY</standard:if>
 * 
 * Tag example:
 * <standard:if condition="${a}>2">BODY</standard:if>
 * 
 * PHP output:
 * <?php if ($a>2) { ?> BODY <?php } ?>
 */
class StandardIfTag extends AbstractParsableTag {
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("condition"));
		return '<?php if ('.$this->parseExpression($tblParameters['condition']).') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}