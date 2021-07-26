<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/   
class GuestBook_Save_Action extends A5_Action_Controller{
  
  public function process(A5_Request $request){  
    $response = new A5_Response();
    if($this->checkLastTimeRequest($request)){
      $recordModel = $this->getRecordModelFromRequest($request);
      $recordModel->save();  
      $data = $recordModel->getData(); 
      $data['datetime'] = date('d-m-Y H:i:s', $data['datetime']); 
      $response->setResult($data);
    } else {
      $response->setError('Данное действие пока не возможно...');
    }     
    $response->emit();
  }
  
  public function checkLastTimeRequest(A5_Request $request){
    global $storag; 
    $lastRequestTime = $storag->getLastRequestTime();    
    $deffTime = strtotime(date('Y-m-d H:i:s')) - $lastRequestTime;   
    if($deffTime < $request->getTimeOut()){
      return false;
    } else {
      return true; 
    }
  }
  
  public function getRecordModelFromRequest(A5_Request $request){  
    return GuestBook_Record_Model::getInstance($request->getAll());  
  }
    
}  
