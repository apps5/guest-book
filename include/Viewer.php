<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
require_once 'lib/smarty/libs/Smarty.class.php';

class A5_Viewer extends Smarty {
	
	function __construct() {
		parent::__construct();

		$THISDIR = dirname(__FILE__);
    $templatesDir = $THISDIR . '/../layouts/';
    $compileDir = $THISDIR . '/../layouts/templates_c/';

		if (!file_exists($compileDir)) {
			mkdir($compileDir, 0777, true);
		}
		$this->setTemplateDir(array($templatesDir));
		$this->setCompileDir($compileDir);		
	}

	public function getTemplatePath($templateName, $moduleName='') {
		$moduleName = str_replace(':', '/', $moduleName);
		$completeFilePath = $this->getTemplateDir(0). DIRECTORY_SEPARATOR . "modules/$moduleName/$templateName";
		if(!empty($moduleName) && file_exists($completeFilePath)) {
			return "modules/$moduleName/$templateName";
		} else {
      return false;
		}
	}
	
	public function view($templateName, $moduleName='', $fetch=false) {
		$templatePath = $this->getTemplatePath($templateName, $moduleName);
		$templateFound = $this->templateExists($templatePath);
		
		if ($templateFound) {
			if($fetch) {
				return $this->fetch($templatePath);
			} else {
				$this->display($templatePath);
			}
			return true;
		}		
		return false;
	}

	static function getInstance() {
		$instance = new self();
		return $instance;
	}

}
