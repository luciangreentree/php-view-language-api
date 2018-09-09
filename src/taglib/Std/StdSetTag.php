<?php
namespace Lucinda\Templating;
/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <std:set name="VARNAME" value="EXPRESSION"/>
*
* Tag example:
* <std:set name="asd" value="16"/>
*
* PHP output:
* <?php $asd = "16"; ?>
*/
class StdSetTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("name","value"))) {
			throw new ViewException("std:set requires parameters: 'name', 'value'");
		}
		return '<?php $'.$parameters['name'].' = '.($this->isExpression($parameters['value'])?$this->parseExpression($parameters['value']):"'".addslashes($parameters['value'])."'").'; ?>';
	}
}