<?php
namespace Lucinda\Templating;

/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <:set var="VARNAME" val="EXPRESSION"/>
*/
class StdSetTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("var","val"))) {
			throw new ViewException(":set requires parameters: 'var', 'val'");
		}
		return '<?php $'.$parameters['var'].' = '.($this->isExpression($parameters['val'])?$this->parseExpression($parameters['val']):"'".addslashes($parameters['val'])."'").'; ?>';
	}
}