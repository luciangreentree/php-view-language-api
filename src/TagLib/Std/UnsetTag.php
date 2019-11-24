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
     * Parses start tag.
     *
     * @param string[string] $parameters
     * @return string
     */
    public function parseStartTag(array $parameters=array()): string
    {
        $this->checkParameters($parameters, array("var"));
        return '<?php unset($'.$parameters['var'].'); ?>';
    }
}
