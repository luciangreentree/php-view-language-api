<?php
namespace Lucinda\Templating;

/**
 * Encapsulates a file and operations on file.
 */
class File
{
    private $path;
    
    /**
     * Creates an object.
     *
     * @param string $name Complete path of file.
     */
    public function __construct($name)
    {
        $this->path = $name;
    }
    
    /**
     * Writes contents to file and creates it if it doesn't exist.
     *
     * @param string $content
     * @throws ViewException If you are not allowed to write on that file.
     */
    public function putContents($content)
    {
        $folder = substr($this->path, 0, strrpos($this->path, "/"));
        if (!file_exists($folder)) {
            $parts = explode("/", $folder);
            $folder = "";
            foreach ($parts as $component) {
                $folder .= $component."/";
                if (!file_exists($folder)) {
                    mkdir($folder);
                }
            }
        }
        $response = @file_put_contents($this->path, $content);
        if (!$response) {
            throw new ViewException("Could not write to file: ".$this->path);
        }
    }
    
    /**
     * Gets contents of file.
     *
     * @throws ViewException If file doesn't exist.
     * @return string
     */
    public function getContents()
    {
        if (!$this->exists()) {
            throw new ViewException("File not found: ".$this->path);
        }
        return file_get_contents($this->path);
    }
    
    /**
     * Checks if file exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->path);
    }
    
    /**
     * Gets modification time.
     *
     * @return integer
     */
    public function getModificationTime()
    {
        return filemtime($this->path);
    }
}
