<?php
/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <standard:while condition="EXPRESSION">BODY</standard:while>
 *
 * Tag example:
 * <standard:while condition="${a}>2">BODY</standard:while>
 *
 * PHP output:
 * <?php while ($a>2) { ?> BODY <?php } ?>
 */
class StandardWhileTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("condition"));
		return '<?php while ('.$this->parseExpression($tblParameters['condition']).') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}