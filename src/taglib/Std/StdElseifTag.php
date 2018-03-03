<?php
/**
 * Implements how an ELSE IF clause is translated into a tag.
*
* Tag syntax:
* <std:elseif condition="EXPRESSION">BODY</std:elseif>
*
* Tag example:
* <std:elseif condition="${a.b}>2">BODY</std:elseif>
*
* PHP output:
* <?php else if ($a["b"]>2) { ?> BODY <?php } ?>
*/
class StdElseifTag extends AbstractTag implements StartTag {
	/**
	 * (non-PHPdoc)
	 * @see StartTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("condition"))) {
			return '<?php } else if (false) { ?>';
		} else {
			return '<?php } else if ('.$this->parseExpression($parameters['condition']).') { ?>';
		}		
	}
}