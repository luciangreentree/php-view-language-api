<?php
/**
 * Response listener for PHP Servlets API that implements View Language logic.
 */
class ViewLanguageResponseListener extends ResponseListener {
	const COMPILATION_VIEWS_PATH = "/tmp/compile";
	
	public function run() {	
		// set paths
		$strViewFile = $this->objResponse->getView();
		if(!$strViewFile) return;
		
		// get output stream from response
		$strOutputStream = $this->objResponse->getOutputStream()->get();
		
		// get views folder
		$strViewsFolder = $this->objApplication->getViewsPath();
		if(!$strViewsFolder) return;

		// get view
		$strViewPath = $strViewsFolder.'/'.$strViewFile.".php";
		$objView = new File($strViewPath);
		$objImportTag = new SystemImportTag($strViewsFolder, $objView->getModificationTime());
		$strOutputStream = $objImportTag->parse($strOutputStream);
		$intViewModifiedTime = $objImportTag->getModifiedTime();
		$objImportTag = null;
		
		// get compilation
		$strCompilationPath = self::COMPILATION_VIEWS_PATH."/".$strViewFile.".php";
		$objCompilation = new File($strCompilationPath);
		if($objCompilation->exists()) {
			$intCompilationModifiedTime = $objCompilation->getModificationTime();
			if($intCompilationModifiedTime >= $intViewModifiedTime) {
				$this->compile($strCompilationPath);
				return;
			}
		}
		
		// start looking for tags whose values should be escaped
		$objEscapeTag = new SystemEscapeTag();
		$objScriptTag = new SystemScriptTag();
		$objStyleTag = new SystemStyleTag();
		
		// remove escaped content
		if($objEscapeTag->hasContent($strOutputStream)) $strOutputStream = $objEscapeTag->removeContent($strOutputStream);
		if($objScriptTag->hasContent($strOutputStream)) $strOutputStream = $objScriptTag->removeContent($strOutputStream);
		if($objStyleTag->hasContent($strOutputStream)) 	$strOutputStream = $objStyleTag->removeContent($strOutputStream);
				
		// run tag parser
		$objTagParser = new TagParser();
		$strOutputStream=$objTagParser->parse($strOutputStream);
		
		// run expression parser
		$objExpressionParser = new ExpressionParser();
		$strOutputStream=$objExpressionParser->parse($strOutputStream);
		
		// restore escaped content
		if($objEscapeTag->hasContent($strOutputStream)) $strOutputStream = $objEscapeTag->restoreContent($strOutputStream);
		if($objScriptTag->hasContent($strOutputStream)) $strOutputStream = $objScriptTag->restoreContent($strOutputStream);
		if($objStyleTag->hasContent($strOutputStream)) 	$strOutputStream = $objStyleTag->restoreContent($strOutputStream);
		
		// save compilation
		$objCompilation->putContents($strOutputStream);
		
		// compile response
		$this->compile($strCompilationPath);
	}
	
	private function compile($strCompilationPath) {
		ob_start();
		$_VIEW = $this->objResponse->toArray();
		try {
			require_once($strCompilationPath);
		} catch(Exception $e) {
			throw new ViewException($e->getMessage());
		}
		$this->objResponse->getOutputStream()->set(ob_get_contents());
		ob_end_clean();
	}
}