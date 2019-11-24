<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\SystemTag;
use Lucinda\Templating\StartTag;

/**
 * Implements how setting an internal variable is translated into a tag.
 *
 * Tag syntax:
 * <:set var="VARNAME" val="EXPRESSION"/>
 */
class SetTag extends SystemTag implements StartTag
{
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        $this->checkParameters($parameters, array("var"));
        return '<?php $'.$parameters['var'].' = '.(isset($parameters['val'])?($this->isExpression($parameters['val'])?$this->parseExpression($parameters['val']):"'".addslashes($parameters['val'])."'"):"null").'; ?>';
    }
}
