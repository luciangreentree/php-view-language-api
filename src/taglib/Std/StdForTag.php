<?php
namespace Lucinda\Templating;
/**
 * Implements how a FOR clause is translated into a tag.
*
* Tag syntax:
* <std:for var="EXPRESSION" value="VARNAME" start="EXPRESSION|INTEGER" end="EXPRESSION|INTEGER" step="INTEGER">BODY</std:for>
*
* Tag example:
* <std:for var="${a}" value="${i}">BODY</std:for>
*
* PHP output:
* <?php for($i=0; i<count($a); $i++) { ?> BODY <?php } ?>
*/
class StdForTag extends AbstractTag implements StartEndTag {
	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseStartTag()
	 */
	public function parseStartTag($parameters=array()) {
		if(!$this->checkParameters($parameters, array("value")) || (isset($parameters['var']) && !$this->isExpression($parameters['var']))) {
			return '<?php for($i=0;$i<0;$i++) { ?>';
		} else {
			return '<?php for($'.$parameters['value'].'='.(isset($parameters['start'])?$this->parseCounter($parameters['start']):0)
			.'; $'.$parameters['value'].'<'.(isset($parameters['end'])?'='.$this->parseCounter($parameters['end']):'sizeof('.$this->parseExpression($parameters['var']).')')
			.'; $'.$parameters['value'].(isset($parameters['step'])?"=".$parameters['value'].($parameters['step']>0?"+".$parameters['step']:$parameters['step']):"++").') { ?>';
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see StartEndTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}

	/**
	 * (non-PHPdoc)
	 * @see AbstractTag::checkParameters()
	 */
	protected function checkParameters($parameters, $requiredParameters) {
		$result = parent::checkParameters($parameters, $requiredParameters);
		if(!$result) return false;
		if(!isset($parameters['end']) && !isset($parameters['var'])) {
			//You must define either 'end' or 'var' attributes for ".get_class($this)."!;
			return false;
		}
		return true;
	}

	/**
	 * Parses start & end attributes, which may be either integers or expressions.
	 *
	 * @param string $expression
	 * @return integer
	 */
	private function parseCounter($expression) {
		if(is_numeric($expression)) 					return $expression;
		else if(!$this->isExpression($expression)) 	return '$'.$expression;
		else 											return $this->parseExpression($expression);
	}
}