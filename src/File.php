<?php
/**
 * Encapsulates a file and operations on file.
 */
class File {
	private $strPath;
	
	/**
	 * Creates an object.
	 * 
	 * @param string $strName Complete path of file.
	 */
	public function __construct($strName) {
		$this->strPath = $strName;
	}
	
	/**
	 * Writes contents to file and creates it if it doesn't exist.
	 * 
	 * @param string $mixContent
	 * @throws ViewException If you are not allowed to write on that file.
	 */
	public function putContents($mixContent) {
		$strFolder = substr($this->strPath, 0,strrpos($this->strPath,"/"));
		if(!file_exists($strFolder)){
			$tblParts = explode("/",$strFolder);
			$strFolder = "";
			foreach($tblParts as $strComponent) {
				$strFolder .= $strComponent."/";
				if(!file_exists($strFolder)) mkdir($strFolder);
			}
		}
		$blnResponse = @file_put_contents($this->strPath, $mixContent);
		if(!$blnResponse) throw new ViewException("Could not write to file: ".$this->strPath);
	}
	
	/**
	 * Gets contents of file. 
	 * 
	 * @throws ViewException If file doesn't exist.
	 * @return string
	 */
	public function getContents() {
		if(!$this->exists()) throw new ViewException("File not found: ".$this->strPath);
		return file_get_contents($this->strPath);
	}
	
	/**
	 * Checks if file exists.
	 * 
	 * @return boolean
	 */
	public function exists() {
		return file_exists($this->strPath);
	}
	
	/**
	 * Gets modification time.
	 * 
	 * @return integer
	 */
	public function getModificationTime() {
		return filemtime($this->strPath);
	}
}