<?php
/*+**********************************************************************************
 * Andrei Maximov
 ************************************************************************************/
class A5_Storag {
  
  private $dataStorag;
  private $itemNumberic = 'itemnumberic';
  private $rootElement = 'storag';
  
  function __construct() {
    $dataStorag = array();
    $storagFile = $this->getStoragFileName();
    if(file_exists($storagFile)){
      $xmlstring = file_get_contents($storagFile);     
      $dataStorag = $this->xmlToArray($xmlstring);    
      $this->dataStorag = $dataStorag[$this->rootElement];     
    } else {
      $this->dataStorag = $dataStorag;
    }	
	}
  
  public static function getInstance(){
    $instance = new self();    
		return $instance;
  }
  
  public function getStoragFileName(){
    global $LOADER_FILE_DIR;
    return $LOADER_FILE_DIR . DIRECTORY_SEPARATOR . 'storag/storag.xml';
  }
    
  public function getStoragData(){
    return $this->dataStorag;
  }
  
  public function getLastRequestTime(){     
    if(isset($this->dataStorag['lastRequestTime'])){
      return $this->dataStorag['lastRequestTime'];
    } else {
      return false;
    }
  }
  
  public function getStoragDataByModule($module){     
    if(isset($this->dataStorag[$module])){
      return $this->dataStorag[$module];
    } else {
      return array();
    }
  }
    
  public function updateRecordsByModule($module, $recordModel){
    global $requestTime;     
    $recordModel->set('datetime', strtotime(date('Y-m-d H:i:s')));  
    $data = $recordModel->getData();
    $this->dataStorag['lastRequestTime'] = $requestTime;
    if($data['record'] && $data['record'] > 0){
      $data['changed'] = 1;
      $recordModel->set('changed', 1);
      $this->dataStorag[$module][$this->itemNumberic.$data['record']] = $data;
    } else {
      $recordModel->set('changed', 0);
      $lastInsertId = $this->getLastInsertIdByModule($module);
      if($lastInsertId > 0){
        $data['record'] = (int)$lastInsertId+1;
      } else {
        $data['record'] = (int)$lastInsertId;
      }
      $data['changed'] = 0;
      $this->dataStorag[$module][$this->itemNumberic.$data['record']] = $data;
    }
    $this->saveStoragData();
    return $data['record'];
  }
  
  public function getLastInsertIdByModule($module){
    if(isset($this->dataStorag[$module])){
      if(function_exists('array_key_last') && count($this->dataStorag[$module]) > 0){
        $lastKey = array_key_last($this->dataStorag[$module]);
        return str_replace($this->itemNumberic, '', $lastKey);
      } else if(count($this->dataStorag[$module]) > 0){
        $keys = array_keys($this->dataStorag[$module]);
        $lastKey = end($keys);
        return str_replace($this->itemNumberic, '', $lastKey);
      } else {
        return 1;
      }
    } else {
      return 1;
    }
  }
    
  public function saveStoragData(){   
    $storagFile = $this->getStoragFileName();
    mb_convert_variables('utf-8', 'original encode', $this->dataStorag);
    $xml =  new SimpleXMLElement('<'.$this->rootElement.'/>');
    $xml = $this->arrayToXML($this->dataStorag, $xml);    
    file_put_contents($storagFile, $xml->asXML());   
  }
  
  function arrayToXml(Array $array, SimpleXMLElement $xml) {
    foreach($array as $key => $value) {
      if (!is_array($value)) {
        (is_numeric($key)) ? $xml->addChild($this->itemNumberic.$key, $value) : $xml->addChild($key, $value);
        continue;
      }
      $xmlChild = (is_numeric($key)) ? $xml->addChild($this->itemNumberic.$key) : $xml->addChild($key);
      $this->arrayToXml($value, $xmlChild);
    }
    return $xml;
  }
  
  public function xmlToArray($xmlstring){          
    $array = array();
    if($xmlstring){
      $xml = simplexml_load_string($xmlstring);                  
      $array = json_decode(json_encode((array)$xml), true);    
      $array = array($xml->getName() => $array);       
    }    
    return $array;
  }
  
} 
