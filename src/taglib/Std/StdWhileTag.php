<?php
namespace Lucinda\Templating;

/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <:while test="EXPRESSION">BODY</:while>
 */
class StdWhileTag extends SystemTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
	    $this->checkParameters($parameters, array("test"));
		return '<?php while('.$this->parseExpression($parameters['test']).') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}