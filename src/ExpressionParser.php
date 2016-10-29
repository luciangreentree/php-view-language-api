<?php
/**
 * Implements scalar expressions that are going to be interpreted as PHP when response is displayed to client.
 * 
 * Example of expression: 
 * 		${request.client.ip}
 * This will be converted to:
 * 		<?php echo $request["client"]["ip"]; ?>
 */
class ExpressionParser {
	/**
	 * Looks for variable expressions in SUBJECT and returns answer where expressions are converted to PHP.
	 * 
	 * @param string $strSubject
	 * @return string
	 */
	public function parse($strSubject) {
		if(strpos($strSubject,'${')===false) {
			return $strSubject;
		}
		return preg_replace_callback("/[\$]\{((?:(?>[^{}]+?)|(?R))*?)\}/",array($this,"parseCallback"),$strSubject);
	}
	
	/**
	 * For each macro-expression found, calls for its conversion to PHP and wraps it up as scriptlet.
	 * 
	 * @param array $tblMatches
	 * @return string
	 */
	protected function parseCallback($tblMatches) {
		$paranthesisPosition = strpos($tblMatches[1],"(");
		if($paranthesisPosition!==false) {
			$functionName = substr($tblMatches[1],0,$paranthesisPosition);
			$variableName = substr($tblMatches[1],$paranthesisPosition+1);
			return '<?php echo '.$functionName.'('.$this->convertToVariable(substr($variableName,0,-1)).'); ?>';
		} else {
			return '<?php echo '.$this->convertToVariable($tblMatches[0]).'; ?>';
		}
	}
	
	/**
	 * Performs conversion of expression to PHP.
	 * 
	 * Example: ${request.ip} is converted to $request['ip']
	 * 
	 * @param string $strDottedVariable
	 * @return string
	 */
	protected function convertToVariable($strDottedVariable) {
		if(strpos($strDottedVariable,".")===false) {
			return str_replace(array("{","}"),"",$strDottedVariable);
		} else {
			return preg_replace(array('/\$\{([a-zA-Z0-9_]+)(\.)?/','/\}/','/\./','/\[([a-zA-Z0-9_]+)\]/','/\[\]/'),array('$$1[',']','][','["$1"]',''),$strDottedVariable);
		}
	}
}