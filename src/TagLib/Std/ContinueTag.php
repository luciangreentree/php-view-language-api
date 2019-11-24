<?php
namespace Lucinda\Templating\TagLib\Std;

use Lucinda\Templating\StartTag;
use Lucinda\Templating\SystemTag;

/**
 * Implements a CONTINUE operation in a loop.
 *
 * Tag syntax:
 * <:continue/>
 */
class ContinueTag extends SystemTag implements StartTag
{
    /**
     * {@inheritDoc}
     * @see StartTag::parseStartTag()
     */
    public function parseStartTag($parameters=array())
    {
        return '<?php continue; ?>';
    }
}
