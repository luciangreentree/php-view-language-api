<?php
namespace Lucinda\Templating;

/**
 * Implements scalar expressions that are going to be interpreted as PHP when response is displayed to client.
 *
 * Example of expression:
 * 		${request.client.ip}
 * This will be converted to:
 * 		<?php echo $request["client"]["ip"]; ?>
 */
class ExpressionParser
{
    /**
     * Looks for variable expressions in SUBJECT and returns answer where expressions are converted to PHP.
     *
     * @param string $subject
     * @return string
     */
    public function parse(string $subject): string
    {
        if (strpos($subject, '${')===false) {
            return $subject;
        }
        return preg_replace_callback("/[\$]\{((?:(?>[^{}]+?)|(?R))*?)\}/", array($this,"parseCallback"), $subject);
    }
    
    /**
     * For each macro-expression found, calls for its conversion to PHP and wraps it up as scriptlet.
     *
     * @param array $matches
     * @return string
     */
    protected function parseCallback(array $matches): string
    {
        $position = strpos($matches[1], "(");
        if ($position!==false) {
            return '<?php echo '.substr($matches[1], 0, $position).$this->convertToVariable(substr($matches[1], $position)).'; ?>';
        } else {
            return '<?php echo '.$this->convertToVariable($matches[0]).'; ?>';
        }
    }
    
    /**
     * Performs conversion of expression to PHP.
     *
     * @param string $dottedVariable
     * @return string
     */
    protected function convertToVariable(string $dottedVariable): string
    {
        if (strpos($dottedVariable, ".")===false) {
            return str_replace(array("{","}"), "", $dottedVariable);
        } else {
            return preg_replace(array('/\$\{([a-zA-Z0-9_]+)(\.)?/','/\}/','/\./','/\[([a-zA-Z0-9_]+)\]/','/\[\]/'), array('$$1[',']','][','["$1"]',''), $dottedVariable);
        }
    }
}
