<?php
namespace Lucinda\Templating;

/**
 * Implements how a FOR clause is translated into a tag.
*
* Tag syntax:
* <:for var="VARNAME" start="EXPRESSION|INTEGER" end="EXPRESSION|INTEGER" step="INTEGER">BODY</:for>
*/
class StdForTag extends SystemTag implements StartEndTag
{
    /**
     * {@inheritDoc}
     * @see StartEndTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("var", "start", "end"));
        return '<?php for($'.$parameters['var'].'='.$this->parseCounter($parameters['start'])
        .'; $'.$parameters['var'].'<='.$this->parseCounter($parameters['end'])
        .'; $'.$parameters['var'].(isset($parameters['step'])?($parameters['step']>0?"+":"-")."=".$parameters['step']:"++").') { ?>';
    }

    /**
     * {@inheritDoc}
     * @see StartEndTag::parseEndTag()
     */
    public function parseEndTag()
    {
        return '<?php } ?>';
    }

    /**
     * Parses start & end attributes, which may be either integers or expressions.
     *
     * @param string $expression
     * @return integer
     */
    private function parseCounter($expression)
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
