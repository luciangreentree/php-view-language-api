<?php
/**
 * Implements how a FOREACH clause is translated into a tag.
 * 
 * Tag syntax:
 * <standard:foreach var="EXPRESSION" key="KEYNAME" value="VALUENAME">BODY</standard:foreach>
 * 
 * Tag example:
 * <standard:foreach var="${a.b}" key="${keyName}" value="${valueName}">BODY</standard:foreach>
 * 
 * PHP output:
 * <?php foreach($a["b"] as $keyName=>$valueName) { ?> BODY <?php } ?>
 */
class StandardForeachTag extends AbstractParsableTag {
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseStartTag()
	 */
	public function parseStartTag($tblParameters=array()) {
		$this->checkParameters($tblParameters, array("var","value"));
		if(!$this->isExpression($tblParameters['var'])) throw new ViewException("Value 'var' must be an expression for ".get_class($this)."!");
		return '<?php foreach('.$this->parseExpression($tblParameters['var']).' as '.(!empty($tblParameters['key'])?'$'.$tblParameters['key'].'=>':'').'$'.$tblParameters['value'].') { ?>';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see AbstractParsableTag::parseEndTag()
	 */
	public function parseEndTag() {
		return '<?php } ?>';
	}
}