<?php



class AjaxResponseMessage{
	var $success = true;
	var $logMessage;
	var $displayMessage;
	
	function appendLogMessage($message){
		$this->logMessage .= $message;
		$this->success = false;
	}
	function appendDisplayMessage($message){
		$this->displayMessage .= $message;
		$this->success = false;
	}
}

?>