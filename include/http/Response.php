<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
class A5_Response {

	static $EMIT_RAW = 0;
	static $EMIT_JSON= 1;
	static $EMIT_HTML= 2;
	private $error = NULL;
	private $result = NULL;
	private $emitType= 1; // EMIT_JSON
	private $headers = array();

	function setHeader($header) {
		$this->headers[] = $header;
	}

	function setError($code, $message=null, $title=null) {
		if ($message == null) $message = $code;
		$error = array('code' => $code, 'message' => $message, 'title' => $title);
		$this->error = $error;
	}

	function setEmitType($type) {
		$this->emitType = $type;
	}

	function isJSON() {
		return $this->emitType == self::$EMIT_JSON;
	}

	function getError() {
		return $this->error;
	}

	function hasError() {
		return !is_null($this->error);
	}

	function setResult($result) {
    if(is_string($result)) {
        $this->result = mb_convert_encoding($result, 'UTF-8', mb_detect_encoding($result, 'auto'));
    } else {
        $this->result = $result;
    }
	}

	function updateResult($key, $value) {
		$this->result[$key] = $value;
	}

	function getResult() {
		return $this->result;
	}

	protected function prepareResponse() {
		$response = array();
		if($this->error !== NULL) {
			$response['success'] = false;
			$response['error'] = $this->error;
		} else {
			$response['success'] = true;
			$response['result'] = $this->result;
		}
		return $response;
	}

	function emit() {

		$contentTypeSent = false;
		foreach ($this->headers as $header) {
			if (!$contentTypeSent && stripos($header, 'content-type') === 0) { $contentTypeSent = true; }
			header($header);
		}

		if ($this->emitType == self::$EMIT_JSON) {
			if (!$contentTypeSent) header('Content-type: text/json; charset=UTF-8');
			$this->emitJSON();
		} else if ($this->emitType == self::$EMIT_HTML){
			if (!$contentTypeSent) header('Content-type: text/html; charset=UTF-8');
			$this->emitRaw();
		} else if ($this->emitType == self::$EMIT_RAW) {
			if (!$contentTypeSent) header('Content-type: text/plain; charset=UTF-8');
			$this->emitRaw();
		} 
	}

	protected function emitJSON() {
		echo json_encode($this->prepareResponse());
	}

	protected function emitRaw() {
		if($this->result === NULL) echo (is_string($this->error))? $this->error : var_export($this->error, true);
		echo $this->result;
	}

} 
