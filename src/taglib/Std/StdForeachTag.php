<?php
namespace Lucinda\Templating;

/**
* Implements how a FOREACH clause is translated into a tag.
*
* Tag syntax:
* <:foreach var="EXPRESSION" key="KEYNAME" val="VALUENAME">BODY</:foreach>
*/
class StdForeachTag extends SystemTag implements StartEndTag
{
    /**
     * {@inheritDoc}
     * @see StartEndTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("var","val"));
        return '<?php foreach('.$this->parseExpression($parameters['var']).' as '.(!empty($parameters['key'])?'$'.$parameters['key'].'=>':'').'$'.$parameters['val'].') { ?>';
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
     * {@inheritDoc}
     * @see SystemTag::checkParameters()
     */
    protected function checkParameters($parameters, $requiredParameters)
    {
        parent::checkParameters($parameters, $requiredParameters);
        if (!$this->isExpression($parameters['var'])) {
            throw new ViewException("Invalid value of 'var' attribute @ ':foreach' tag: ".$parameters['var']);
        }
        return true;
    }
}
