<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
class GuestBook_Record_Model {
  
  private $valuemap;
  private $allowedFields = array('record', 'parent_record', 'name_user', 'message_user', 'changed');
  
  function __construct($values = array()) {
    foreach ($values as $key => $value) {
      if(in_array($key, $this->allowedFields)){
        if($key == 'parent_record' && !$value){
          $value = 0;
        }
        $this->set($key, $value);
      }    
    }
  }
  
  static function getInstance($values = array()) {
		$instance = new self($values);
		return $instance;
	}
  
  function getData() {
    return $this->valuemap;
  }
    
  function get($key) {  
		if(isset($this->valuemap[$key])) {
			$value = $this->valuemap[$key];
			return $value;
		}
	}
  
  function has($key) {
		return isset($this->valuemap[$key]);
	}

	function set($key, $newvalue) {
		$this->valuemap[$key]= $newvalue;
	}
  
  public function save(){    
    global $storag; 
    $recordId = $storag->updateRecordsByModule('GuestBook', $this);
    if(!$this->get('record')){
      $this->set('record', $recordId);
    }
    return $this;  
  } 
      
}
