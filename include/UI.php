<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
require_once "include/Loader.php";
require_once "include/storag/Storag.php";
require_once "include/Viewer.php";
require_once "include/Controller.php";
require_once "include/http/Request.php";
require_once "include/http/Response.php";
require_once "include/Exception.php";

class A5_UI {
  
  public function process(A5_Request $request){  
    global $storag, $requestTime;
    
    $requestTime = strtotime(date('Y-m-d H:i:s'));
    $module = $request->get('module');
    $view = $request->get('view');
		$action = $request->get('action');
		$response = false;
    
    if(empty($module)){
      $module = 'GuestBook';
      $request->set('module', $module);
    }
    if(empty($view) && empty($action)){
      $view = 'List';
      $request->set('view', $view);
    }
    
    if (!empty($action)) {
      $componentType = 'Action';
      $componentName = $action;
    } else {
      $componentType = 'View';
      $componentName = $view;
    }
    
    try {
      $handlerClass = A5_Loader::getComponentClassName($componentType, $componentName, $module);
      $handler = new $handlerClass();
      if (isset($handler)) {
        $storag = A5_Storag::getInstance();  
        $handler->validateRequest($request);  
        $this->triggerPreProcess($handler, $request);
        $response = $handler->process($request);
        $this->triggerPostProcess($handler, $request);
      } else {
        throw new A5_Exception('Class handler not found...');
      } 
    } catch(Exception $e) {
			if ($view) {
				$viewer = A5_Viewer::getInstance();
				$viewer->assign('MESSAGE', $e->getMessage());
				$viewer->view('NotOperation.tpl', 'GuestBook');
			} else {
				$response = new A5_Response();
				$response->setEmitType(A5_Response::$EMIT_JSON);
				$response->setError($e->getMessage());
			}
		}    
    if ($response) {
			$response->emit();
		}    
  }
  
  protected function triggerPreProcess($handler, $request) {
		if($request->isAjax()){
			return true;
		}
		$handler->preProcess($request);
	}

	protected function triggerPostProcess($handler, $request) {
		if($request->isAjax()){
			return true;
		}
		$handler->postProcess($request);
	}
  
} 
