<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
class A5_Request {

	private $valuemap;
	private $timeOut = 10;
  
	function __construct($values) {
		$this->valuemap = $values;
	}
	
	function getTimeOut() {  
		return $this->timeOut;
	}
  
	function get($key) {  
		if(isset($this->valuemap[$key])) {
			$value = $this->valuemap[$key];
			return $value;
		}
	}

	function getAll() {
		return $this->valuemap;
	}

	function has($key) {
		return isset($this->valuemap[$key]);
	}

	function set($key, $newvalue) {
		$this->valuemap[$key]= $newvalue;
	}

	function isAjax() {
		if(!empty($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == true) {
			return true;
		} elseif(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			return true;
		}
		return false;
	}
	
	function validateReadAccess(){
		return true;
	}

} 
