<?php
/**
 * Implements how a SWITCH clause is translated into a tag. Tag body can only contain <standard:case> tags.
 *
 * Tag syntax:
 * <standard:switch var="EXPRESSION">CASES</standard:switch>
 *
 * Tag example:
 * <standard:switch var="${a}">CASES</standard:switch>
 *
 * PHP output:
 * <?php switch ($a) { ?> ... <?php } ?>
 */
class StandardSwitchTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("var"));
		return '<?php switch ('.$this->parseExpression($tblParameters['var']).') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}