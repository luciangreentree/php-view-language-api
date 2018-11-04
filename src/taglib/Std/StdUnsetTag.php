<?php
namespace Lucinda\Templating;


/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <:unset name="VARNAME"/>
*
* Tag example:
* <:set name="asd" value="16"/>
*
* PHP output:
* <?php unset($asd); ?>
*/
class StdUnsetTag extends SystemTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("name"))) {
			throw new ViewException(":unset requires parameters: 'name'");
		}
		return '<?php unset($'.$parameters['name'].'); ?>';
	}
}