<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\SystemTag;
use Lucinda\Templating\StartTag;

/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <:unset var="VARNAME"/>
*/
class UnsetTag extends SystemTag implements StartTag
{
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("var"));
        return '<?php unset($'.$parameters['var'].'); ?>';
    }
}
