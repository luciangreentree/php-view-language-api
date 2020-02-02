<?php
namespace Lucinda\Templating;

/**
 * Implements the blueprint of a tag that expects both start & end tags.
 */
interface StartEndTag extends StartTag
{
    /**
     * Parses end tag.
     *
     * @return string
     */
    public function parseEndTag(): string;
}
