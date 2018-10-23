<?php
namespace Lucinda\Templating;
require_once("AbstractTag.php");
require_once("UserTag.php");

/**
 * Implements logical expressions that are going to be interpreted as PHP when response is displayed to client.
 *
 * Example of tag:
 * 		<std:if condition="${request.server.ip}=='127.0.0.1'">
 * 			You are on localhost!
 * 		</std:if>
 * Is converted to:
 * 		<?php if($request["client"]["ip"]=='127.0.0.1') { ?>
 * 			You are on localhost!
 * 		<?php } ?>
 */
class TagParser {
    private $tagLibFolder;
    private $tagExtension;
    private $viewCompilation;
    
    /**
     * Creates a tag parser instance.
     *
     * @param string $tagLibFolder Folder containing user-defined taglibs.
     * @param string $tagExtension Extension of user-defined tags.
     * @param ViewCompilation $viewCompilation Object that collects components that take part in view.
     */
    public function __construct($tagLibFolder, $tagExtension, ViewCompilation $viewCompilation) {
        $this->tagLibFolder = $tagLibFolder;
        $this->tagExtension = $tagExtension;
        $this->viewCompilation = $viewCompilation;
    }
    
    /**
     * Looks for tags in views and returns an answer where each found match is converted to PHP.
     *
     * @param string $subject
     * @param SystemEscapeTag $escaper
     * @return string
     */
    public function parse($subject, SystemEscapeTag $escaper) {
        // match start & end tags
        $subject = preg_replace_callback("/<([a-zA-Z0-9\-_.]+)\:([a-zA-Z0-9\-_.]+)(\s*(.*)\s*=\s*\"(.*)\"\s*)?\/?>/",array($this,"parseStartTagCallback"),$subject);
        $subject = preg_replace_callback("/<\/([a-zA-Z0-9\-_.]+)\:([a-zA-Z0-9\-_.]+)>/",array($this,"parseEndTagCallback"),$subject);
        $subject = $escaper->backup($subject);
        
        // if it still contains tags, recurse until all tags are parsed
        if(preg_match("/<([a-zA-Z\-]+)\:([a-zA-Z\-]+)(.*?)>/",$subject)!=0) {
            $subject = $this->parse($subject, $escaper);
        }
        
        return $subject;
    }
    
    /**
     * Calls for conversion task for each start tag found and returns converted answer. This is done by delegating conversion to detected tag class.
     *
     * @param array $matches
     * @return string
     */
    protected function parseStartTagCallback($matches) {
        return $this->getTagInstance($matches)->parseStartTag(isset($matches[3])?$this->getTagParameters($matches[3]):array());
    }
    
    /**
     * Calls for conversion task for each end tag found and returns converted answer. This is done by delegating conversion to detected tag class.
     *
     * @param array $matches
     * @return string
     */
    protected function parseEndTagCallback($matches) {
        return $this->getTagInstance($matches)->parseEndTag();
    }
    
    /**
     * Detects tag class from tag declaration.
     *
     * Example:
     * 		<std:for ...>
     *
     * Where:
     * 		- "std" is the name of tag library
     * 		- "for" is the name of tag function
     *
     * Detected class name will be:
     * 		StdForTag
     *
     * @param array $matches
     * @throws ViewException
     * @return AbstractTag
     */
    private function getTagInstance($matches) {
        if(strtolower($matches[1])=="std") {
            $libraryName = str_replace(" ","",ucwords(str_replace("-"," ",strtolower($matches[1]))));
            $tagName = str_replace(" ","",ucwords(str_replace("-"," ",strtolower($matches[2]))));
            $className = "\\Lucinda\\Templating\\".$libraryName.$tagName.'Tag';
            if(!class_exists($className)) throw new ViewException("Tag not found: ".$className);
            return new $className();
        } else {
            $libraryName = str_replace(" ","",strtolower($matches[1]));
            $tagName = str_replace(" ","",strtolower($matches[2]));
            $tagFolder = $this->tagLibFolder;
            if(!$tagFolder) {
                if(isset($matches[3]) && preg_match('/namespace\s*=\s*"([^"]+)"/', $matches[3], $mt)) {
                    $tagFolder = $mt[1];
                } else {
                    throw new ViewException("Tags folder not found!");
                }
            }
            $fileLocation = $tagFolder."/".$libraryName."/".$tagName.".".$this->tagExtension;
            if(!file_exists($fileLocation)) throw new ViewException("Tag not found: ".$libraryName."/".$tagName);
            $this->viewCompilation->addComponent($fileLocation);
            return new UserTag($fileLocation);
        }
    }
    
    /**
     * Detects tag attributes from tag declaration.
     *
     * Example:
     * 		<std:for  var="${asd}" value="i" >
     *
     * Parameters detected will be:
     * 		var
     * 		value
     *
     * @param string $parameters
     * @return array
     */
    private function getTagParameters($parameters) {
        $parameters = trim($parameters);
        if(!$parameters || $parameters=="/") return array();
        preg_match_all('/([a-zA-Z0-9\-_.]+)\s*=\s*"([^"]+)"/', $parameters, $parameters, PREG_SET_ORDER);
        $output=array();
        foreach($parameters as $values) {
            $output[trim($values[1])]=trim($values[2]);
        }
        return $output;
    }
}
