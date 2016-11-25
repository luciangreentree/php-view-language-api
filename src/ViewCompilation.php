<?php
/**
 * Abstracts view compilation logic.
*/
class ViewCompilation {
	private $strCompilationPath;
	private $strChecksumPath;
	private $components = array();

	/**
	 * Creates a compilation instance.
	 *
	 * @param string $strCompilationsFolder
	 * @param string $strTemplatePath
	 * @param string $strTemplatesExtension
	 */
	public function __construct($strCompilationsFolder, $strTemplatePath, $strTemplatesExtension) {
		$this->strCompilationPath = $strCompilationsFolder."/".$strTemplatePath.".".$strTemplatesExtension;
		$this->strChecksumPath = $strCompilationsFolder."/checksums/".crc32($strTemplatePath).".crc";
		// preset components referenced in checksum
		$objFile = new File($this->strChecksumPath);
		if($objFile->exists()) {
			$strContents = $objFile->getContents();
			$this->components = explode(",", $strContents);
		}
	}

	/**
	 * Checks if any of compilation components have changed since last update.
	 */
	public function hasChanged() {
		$objCompilation = new File($this->strCompilationPath);
		if(!empty($this->components)) {
			if($objCompilation->exists()) {
				if($objCompilation->getModificationTime() >= $this->getLatestModificationTime()) {
					return false;
				}
			}
			// reset components
			$this->components = array();
		}
		return true;
	}

	/**
	 * Adds a compilation component (template / tag)
	 *
	 * @param string $strPath Path to component.
	 */
	public function addComponent($strPath) {
		$this->components[] = $strPath;
	}

	/**
	 * Gets latest modification time of compilation components.
	 *
	 * @return integer
	 */
	private function getLatestModificationTime() {
		$intLatestDate = 0;
		foreach($this->components as $strFile) {
			$intTime = filemtime($strFile);
			if($intTime>$intLatestDate) $intLatestDate = $intTime;
		}
		return $intLatestDate;
	}

	/**
	 * Saves compilation & its checksum to disk.
	 *
	 * @param string $strOutputStream
	 */
	public function save($strOutputStream) {
		// saves checksum
		$objFile = new File($this->strChecksumPath);
		$objFile->putContents(implode(",", $this->components));

		// saves compilation
		$objCompilation = new File($this->strCompilationPath);
		$objCompilation->putContents($strOutputStream);
	}

	/**
	 * Gets compilation file path.
	 *
	 * @return string
	 */
	public function getCompilationPath() {
		return $this->strCompilationPath;
	}
}
