<?php
/**
 * Response listener for PHP Servlets API that implements View Language logic.
 */
class ViewLanguageResponseListener extends ResponseListener {
	const COMPILATION_VIEWS_PATH = "/tmp/compile";
	
	public function run() {	
		// compile
		$vlp = new ViewLanguageParser($this->objApplication->getViewsPath(), $this->objResponse->getView(), self::COMPILATION_VIEWS_PATH);
		$strCompilationPath = $vlp->parse($this->objResponse->getOutputStream()->get());
		
		// save changes
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