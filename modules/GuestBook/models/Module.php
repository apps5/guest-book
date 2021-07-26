<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
class GuestBook_Module_Model {
  protected $name;  
  
  function __construct() {
		$this->name = 'GuestBook';
	}
  
  public function getName(){
    return $this->name;
  }
  
  static function getInstance() {
		$instance = new self();
		return $instance;
	}
  
  public function getListAll(){
    global $storag;
    $listRecord = $storag->getStoragDataByModule($this->getName());                    
    return $listRecord;                      
  }
  
  public function getListHeadRecords($listRecords){
    $listHeadRecords = array();
    foreach ($listRecords as $key => $record) {
      if($record['parent_record']){
        continue;
      } else {
        $listHeadRecords[] = $record;
      }
    } 
    if(count($listHeadRecords) > 0){
      return array_reverse($listHeadRecords);
    } else {
      return $listHeadRecords;
    }  
  }

} 
