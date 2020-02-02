<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\StartEndTag;
use Lucinda\Templating\SystemTag;

/**
 * Implements how a FOR clause is translated into a tag.
*
* Tag syntax:
* <:for var="VARNAME" start="EXPRESSION|INTEGER" end="EXPRESSION|INTEGER" step="INTEGER">BODY</:for>
*/
class ForTag extends SystemTag implements StartEndTag
{
    /**
     * Parses start tag.
     *
     * @param string[string] $parameters
     * @return string
     * @throws ViewException If required parameters aren't supplied
     */
    public function parseStartTag(array $parameters=array()): string
    {
        $this->checkParameters($parameters, array("var", "start", "end"));
        return '<?php for ($'.$parameters['var'].'='.$this->parseCounter($parameters['start'])
        .'; $'.$parameters['var'].'<='.$this->parseCounter($parameters['end'])
        .'; $'.$parameters['var'].(isset($parameters['step'])?($parameters['step']>0?"+":"-")."=".$parameters['step']:"++").') { ?>';
    }

    /**
     * Parses end tag.
     *
     * @return string
     */
    public function parseEndTag(): string
    {
        return '<?php } ?>';
    }

    /**
     * Parses start & end attributes, which may be either integers or expressions.
     *
     * @param string $expression
     * @return integer
     */
    private function parseCounter(string $expression): int
    {
        if (is_numeric($expression)) {
            return $expression;
        } elseif (!$this->isExpression($expression)) {
            return '$'.$expression;
        } else {
            return $this->parseExpression($expression);
        }
    }
}
