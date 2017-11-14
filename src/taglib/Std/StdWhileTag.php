<?php
/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <std:while condition="EXPRESSION">BODY</std:while>
 *
 * Tag example:
 * <std:while condition="${a}>2">BODY</std:while>
 *
 * PHP output:
 * <?php while ($a>2) { ?> BODY <?php } ?>
 */
class StdWhileTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		if(!$this->checkParameters($tblParameters, array("condition"))) {
			return '<?php while(false) { ?>';
		} else {
			return '<?php while('.$this->parseExpression($tblParameters['condition']).') { ?>';
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