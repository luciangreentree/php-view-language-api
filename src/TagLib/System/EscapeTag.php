<?php
namespace Lucinda\Templating\TagLib\System;

/**
 * Implements a tag whose body is not parsable (into other tags or expressions).
 *
 * Tag syntax:
 * <escape>BODY</:escape>
 */
class EscapeTag
{
    private $matches=array();
    private $counter=0;
    
    /**
     * Locates escape tags, remembers their content and replaces them with placeholders.
     *
     * @param string $subject
     * @return string
     */
    public function backup(string $subject): string
    {
        return preg_replace_callback("/\<escape\>(.*?)\<\/escape\>/si", function ($matches) {
            $this->matches[] = $matches[1];
            ++$this->counter;
            return "<bkp>".($this->counter-1)."</bkp>";
        }, $subject);
    }
    
    /**
     * Locates placeholders and replaces them with remembered escape tag bodies.
     *
     * @param string $subject
     * @return string
     */
    public function restore(string $subject): string
    {
        // if no escape tags were found, do not continue
        if ($this->counter == 0) {
            return $subject;
        }
        
        // restore content of escape tags
        return preg_replace_callback("/\<bkp\>(.*?)\<\/bkp\>/si", function ($matches) {
            return $this->matches[$matches[1]];
        }, $subject);
    }
}
