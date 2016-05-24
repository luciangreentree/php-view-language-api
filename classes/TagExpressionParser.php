<?php
/**
 * Implements scalar expressions found in parsable tag attribute values that are going to be interpreted as PHP when response is displayed to client.
 */
class TagExpressionParser extends ExpressionParser {
	/**
	 * @see ExpressionParser::parseCallback()
	 */
	protected function parseCallback($tblMatches) {
		return $this->convertToVariable($tblMatches[0]);
	}
}