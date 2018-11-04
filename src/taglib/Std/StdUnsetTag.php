<?php
namespace Lucinda\Templating;


/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <:unset var="VARNAME"/>
*/
class StdUnsetTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("var"))) {
			throw new ViewException(":unset requires parameters: 'var'");
		}
		return '<?php unset($'.$parameters['var'].'); ?>';
	}
}