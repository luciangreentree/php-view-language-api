<?php
/**
 * Implements how a FOR clause is translated into a tag.
 *
 * Tag syntax:
 * <standard:for var="EXPRESSION" value="VARNAME" start="EXPRESSION|INTEGER" end="EXPRESSION|INTEGER" step="INTEGER">BODY</standard:for>
 * 
 * Tag example:
 * <standard:for var="${a}" value="${i}">BODY</standard:for>
 *
 * PHP output:
 * <?php for($i=0; i<count($a); $i++) { ?> BODY <?php } ?>
 */
class StandardForTag extends AbstractParsableTag {
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("value"));
		if(isset($tblParameters['var']) && !$this->isExpression($tblParameters['var'])) throw new ViewException("Value 'var' must be an expression for ".get_class($this)."!");
		if(isset($tblParameters['start']) && !is_numeric($tblParameters['start'])) 		throw new ViewException("Value 'start' must be a number!");
		if(isset($tblParameters['end']) && !is_numeric($tblParameters['end'])) 			throw new ViewException("Value 'end' must be a number!");
		if(isset($tblParameters['step']) && !is_numeric($tblParameters['step'])) 		throw new ViewException("Value 'step' must be a number!");
		return '<?php for($'.$tblParameters['value'].'='.(isset($tblParameters['start'])?$this->parseCounter($tblParameters['start']):0)
		.'; $'.$tblParameters['value'].'<'.(isset($tblParameters['end'])?'='.$this->parseCounter($tblParameters['end']):'sizeof('.$this->parseExpression($tblParameters['var']).')')
		.'; $'.$tblParameters['value'].(isset($tblParameters['step'])?"=".$tblParameters['value'].($tblParameters['step']>0?"+".$tblParameters['step']:$tblParameters['step']):"++").') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::checkParameters()
	 */
	protected function checkParameters($tblParameters, $tblRequiredParameters) {
		parent::checkParameters($tblParameters, $tblRequiredParameters);
		if(!isset($tblParameters['end']) && !isset($tblParameters['var'])) throw new ViewException("You must define either 'end' or 'var' attributes for ".get_class($this)."!");
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