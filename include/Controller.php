<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
abstract class A5_Controller {
	function __construct() { }
	abstract function process (A5_Request $request);	
	function validateRequest(A5_Request $request) {}    
} 

abstract class A5_Action_Controller extends A5_Controller {
	function __construct() {
		parent::__construct();
	}

	function getViewer(A5_Request $request) {
		throw new A5_Exception ('Action - No getViewer implementation...');
	}
	
	function validateRequest(A5_Request $request) {
		return $request->validateReadAccess();
	}

}

abstract class A5_View_Controller extends A5_Action_Controller {
  protected $viewer;  
	function __construct() {
		parent::__construct();
	}

	function getViewer(A5_Request $request) {
		if(!$this->viewer) {
			$viewer = A5_Viewer::getInstance();
			$viewer->assign('APP_TITLE', 'Гостевая книга');
			$viewer->assign('MODULE', $request->get('module'));
			$viewer->assign('VIEW', $request->get('view'));
			$this->viewer = $viewer;
		}
		return $this->viewer;
	}
  
  function preProcess(A5_Request $request, $display=true) {
    $viewer = $this->getViewer($request);
    if($display) {
      $this->preProcessDisplay($request);
    }
  }

  protected function preProcessTplName(A5_Request $request) {
    return 'Header.tpl';
  }

  protected function preProcessDisplay(A5_Request $request) {
    $viewer = $this->getViewer($request);
    $viewer->view($this->preProcessTplName($request), $request->get('module'));
  }

  function postProcess(A5_Request $request) {    
    $viewer = $this->getViewer($request);
		$viewer->assign('SCRIPTS',$this->getScripts($request));
    $viewer->view('Footer.tpl', $request->get('module'));
  }
	
	function getScripts(A5_Request $request){
		$moduleName = $request->get('module');
		$view = $request->get('view');
    $jsFileNames = array(
      "modules.$moduleName.resources.".$view,
    ); 		   
    $jsScriptInstances = $this->checkJsScripts($jsFileNames); 	
		return $jsScriptInstances;
	}
	
	function checkJsScripts($jsFileNames) {
		$fileExtension = 'js';
		$jsScriptInstances = array();
		if($jsFileNames) {
			foreach($jsFileNames as $jsFileName) {
				$completeFilePath = A5_Loader::resolveNameToPath($jsFileName, $fileExtension);			
				if(file_exists($completeFilePath)) {
					$filePath = 'layouts/'.str_replace('.','/', $jsFileName) . '.'.$fileExtension;
					$jsScriptInstances[$jsFileName] = $filePath;
				} 
			}
		}
		return $jsScriptInstances;
	}

}
