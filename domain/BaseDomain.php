<?php
abstract class BaseDomain {
	
	private $errorCode;
	
	private $errorMessage;
	
	public function setErrorCode($errorCode) {
		$this->errorCode=$errorCode;
	}
	
	public function getErrorCode() {
		return ($this->errorCode);
	}
	
	public function setErrorMessage($errorMessage) {
		$this->errorMessage=$errorMessage;
	}
	
	public function getErrorMessage($errorMessage) {
		return ($this->errorMessage);
	}
}
?>