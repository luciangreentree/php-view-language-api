<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\StartTag;
use Lucinda\Templating\SystemTag;

/**
 * Implements a BREAK operation in a loop.
 *
 * Tag syntax:
 * <:break/>
 */
class BreakTag extends SystemTag implements StartTag
{
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        return '<?php break; ?>';
    }
}
