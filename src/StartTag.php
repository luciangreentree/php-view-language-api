<?php
namespace Lucinda\Templating;

/**
 * Implements the blueprint of a tag that expects no end tag.
 */
interface StartTag
{
    /**
     * Parses start tag.
     *
     * @param string[string] $parameters
     * @return string
     */
    public function parseStartTag(array $parameters=array()): string;
}
