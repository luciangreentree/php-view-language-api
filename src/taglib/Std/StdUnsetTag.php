<?php
namespace Lucinda\Templating;

/**
 * Implements how setting an internal variable is translated into a tag.
*
* Tag syntax:
* <:unset var="VARNAME"/>
*/
class StdUnsetTag extends SystemTag implements StartTag
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
