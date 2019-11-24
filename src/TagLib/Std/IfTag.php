<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\SystemTag;
use Lucinda\Templating\StartEndTag;

/**
 * Implements how an IF clause is translated into a tag.
*
* Tag syntax:
* <:if test="EXPRESSION">BODY</:if>
*/
class IfTag extends SystemTag implements StartEndTag
{
    /**
     * {@inheritDoc}
     * @see StartEndTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("test"));
        return '<?php if ('.$this->parseExpression($parameters['test']).') { ?>';
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
