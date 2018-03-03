<?php
/**
 * Implements how a CASE clause is translated into a tag. Parent can only be a <std:switch> tag.
 *
 * Tag syntax:
 * <std:case value="EXPRESSION|STRING">BODY</std:case>
 *
 * Tag example:
 * <std:case value="2">BODY</std:case>
 *
 * PHP output:
 * <?php case "2": ?> ... <?php break; ?>
 */
class StdCaseTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!empty($parameters["value"])) {
			return '<?php case '.($this->isExpression($parameters['value'])?$this->parseExpression($parameters['value']):"'".addslashes($parameters['value'])."'").': ?>';
		} else {
			return '<?php default: ?>';
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php break; ?>';
	}
}