<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\SystemTag;
use Lucinda\Templating\StartEndTag;
use Lucinda\Templating\ViewException;

/**
 * Implements how a WHILE clause is translated into a tag.
 *
 * Tag syntax:
 * <:while test="EXPRESSION">BODY</:while>
 */
class WhileTag extends SystemTag implements StartEndTag
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
        return '<?php while ('.$this->parseExpression($parameters['test']).') { ?>';
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
