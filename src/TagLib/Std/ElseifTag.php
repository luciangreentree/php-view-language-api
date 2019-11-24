<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\StartTag;
use Lucinda\Templating\SystemTag;

/**
 * Implements how an ELSE IF clause is translated into a tag.
*
* Tag syntax:
* <:elseif test="EXPRESSION">BODY
*/
class ElseifTag extends SystemTag implements StartTag
{
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("test"));
        return '<?php } else if ('.$this->parseExpression($parameters['test']).') { ?>';
    }
}
