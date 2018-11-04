<?php
namespace Lucinda\Templating;

/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <:while condition="EXPRESSION">BODY</:while>
 *
 * Tag example:
 * <:while condition="${a}>2">BODY</:while>
 *
 * PHP output:
 * <?php while ($a>2) { ?> BODY <?php } ?>
 */
class StdWhileTag extends SystemTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("condition"))) {
			return '<?php while(false) { ?>';
		} else {
			return '<?php while('.$this->parseExpression($parameters['condition']).') { ?>';
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