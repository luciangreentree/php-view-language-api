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
     * Parses start tag.
     *
     * @param string[string] $parameters
     * @return string
     * @throws ViewException If required parameters aren't supplied
     */
    public function parseStartTag(array $parameters=array()): string
    {
        $this->checkParameters($parameters, array("test"));
        return '<?php if ('.$this->parseExpression($parameters['test']).') { ?>';
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
}
