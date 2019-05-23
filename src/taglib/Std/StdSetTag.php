<?php
namespace Lucinda\Templating;

/**
 * Implements how setting an internal variable is translated into a tag.
 *
 * Tag syntax:
 * <:set var="VARNAME" val="EXPRESSION"/>
 */
class StdSetTag extends SystemTag implements StartTag {
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array()) {
        $this->checkParameters($parameters, array("var"));
        return '<?php $'.$parameters['var'].' = '.(isset($parameters['val'])?($this->isExpression($parameters['val'])?$this->parseExpression($parameters['val']):"'".addslashes($parameters['val'])."'"):"null").'; ?>';
    }
}