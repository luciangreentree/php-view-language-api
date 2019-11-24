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
     * Parses start tag.
     *
     * @param string[string] $parameters
     * @return string
     */
    public function parseStartTag(array $parameters=array()): string
    {
        return '<?php continue; ?>';
    }
}
