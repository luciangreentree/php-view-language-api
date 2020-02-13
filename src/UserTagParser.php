<?php
namespace Lucinda\Templating;

use Lucinda\Templating\TagLib\System\EscapeTag;
use Lucinda\Templating\TagLib\System\NamespaceTag;

/**
 * Parses user-defined tags and appends them to compilation
 */
class UserTagParser
{
    private $tagExtension;
    private $viewCompilation;
    private $namespaces;
    private $attributesParser;
    
    /**
     * Creates a tag parser instance.
     *
     * @param NamespaceTag $namespaces Object that encapsulates location of user-defined tag libraries.
     * @param string $tagLibFolder Folder containing user-defined taglibs.
     * @param string $tagExtension Extension of user-defined tags.
     * @param ViewCompilation $viewCompilation Object that collects components that take part in view.
     */
    public function __construct(NamespaceTag $namespaces, string $tagExtension, ViewCompilation $viewCompilation)
    {
        $this->namespaces = $namespaces;
        $this->tagExtension = $tagExtension;
        $this->viewCompilation = $viewCompilation;
        $this->attributesParser = new AttributesParser();
    }
    
    /**
     * Looks for tags in views and returns an answer where each found match is converted to PHP.
     *
     * @param string $subject
     * @param EscapeTag $escaper
     * @return string
     */
    public function parse(string $subject, EscapeTag $escaper): string
    {
        // match start & end tags
        $subject = preg_replace_callback("/<([a-zA-Z0-9\-_.]+)\:([a-zA-Z0-9\-_.]+)\s*([^>]+)?>/", function ($matches) {
            return $this->getTagInstance($matches)->parseStartTag(isset($matches[3])?$this->attributesParser->parse($matches[3]):array());
        }, $subject);
        $subject = $escaper->backup($subject);
        
        // if it still contains tags, recurse until all tags are parsed
        if (preg_match("/<([a-zA-Z\-]+)\:([a-zA-Z\-]+)(.*?)>/", $subject)!=0) {
            $subject = $this->parse($subject, $escaper);
        }
        
        return $subject;
    }
    
    /**
     * Detects tag instance from tag declaration.
     *
     * @param array $matches
     * @throws ViewException
     * @return UserTag
     */
    private function getTagInstance(array $matches): UserTag
    {
        $libraryName = $matches[1];
        $tagName = $matches[2];
        $tagFolder = $this->namespaces->get($libraryName);
        $fileLocation = $tagFolder."/".$libraryName."/".$tagName.".".$this->tagExtension;
        if (!file_exists($fileLocation)) {
            throw new ViewException("User tag not found: ".$libraryName."/".$tagName);
        }
        $this->viewCompilation->addComponent($fileLocation);
        return new UserTag($fileLocation);
    }
}
