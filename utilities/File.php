<?php
class File {
	private $strPath;
	
	public function __construct($strName) {
		$this->strPath = $strName;
	}
	
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
	
	public function getContents() {
		if(!$this->exists()) throw new ViewException("File not found: ".$this->strPath);
		return file_get_contents($this->strPath);
	}
	
	public function exists() {
		return file_exists($this->strPath);
	}
	
	public function getModificationTime() {
		if(!$this->exists()) throw new ViewException("File not found: ".$this->strPath);
		return filemtime($this->strPath);
	}
}