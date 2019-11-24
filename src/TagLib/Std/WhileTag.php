<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\SystemTag;
use Lucinda\Templating\StartEndTag;

/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <:while test="EXPRESSION">BODY</:while>
 */
class WhileTag extends SystemTag implements StartEndTag
{
    /**
     * {@inheritDoc}
     * @see StartEndTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("test"));
        return '<?php while('.$this->parseExpression($parameters['test']).') { ?>';
    }
    
    /**
     * {@inheritDoc}
     * @see StartEndTag::parseEndTag()
     */
    public function parseEndTag()
    {
        return '<?php } ?>';
    }
}
