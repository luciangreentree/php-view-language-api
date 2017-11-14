<?php
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
	public function parseStartTag($tblParameters=array()) {
		if(!$this->checkParameters($tblParameters, array("value")) || (isset($tblParameters['var']) && !$this->isExpression($tblParameters['var']))) {
			return '<?php for($i=0;$i<0;$i++) { ?>';
		} else {
			return '<?php for($'.$tblParameters['value'].'='.(isset($tblParameters['start'])?$this->parseCounter($tblParameters['start']):0)
			.'; $'.$tblParameters['value'].'<'.(isset($tblParameters['end'])?'='.$this->parseCounter($tblParameters['end']):'sizeof('.$this->parseExpression($tblParameters['var']).')')
			.'; $'.$tblParameters['value'].(isset($tblParameters['step'])?"=".$tblParameters['value'].($tblParameters['step']>0?"+".$tblParameters['step']:$tblParameters['step']):"++").') { ?>';
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
	protected function checkParameters($tblParameters, $tblRequiredParameters) {
		$result = parent::checkParameters($tblParameters, $tblRequiredParameters);
		if(!$result) return false;
		if(!isset($tblParameters['end']) && !isset($tblParameters['var'])) {
			//You must define either 'end' or 'var' attributes for ".get_class($this)."!;
			return false;
		}
		return true;
	}

	/**
	 * Parses start & end attributes, which may be either integers or expressions.
	 *
	 * @param string $strExpression
	 * @return integer
	 */
	private function parseCounter($strExpression) {
		if(is_numeric($strExpression)) 					return $strExpression;
		else if(!$this->isExpression($strExpression)) 	return '$'.$strExpression;
		else 											return $this->parseExpression($strExpression);
	}
}