<?php
/**
 * Implements how a SWITCH clause is translated into a tag. Tag body can only contain <std:case> tags.
 *
 * Tag syntax:
 * <std:switch var="EXPRESSION">CASES</std:switch>
 *
 * Tag example:
 * <std:switch var="${a}">CASES</std:switch>
 *
 * PHP output:
 * <?php switch ($a) { ?> ... <?php } ?>
 */
class StdSwitchTag extends AbstractTag implements StartEndTag {
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