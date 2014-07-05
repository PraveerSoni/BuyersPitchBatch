<?php
require_once (ROOT_PATH."exception/CoreException.php");

/**
 * Enter description here ...
 * @author PREM
 *
 */
class ValidationException extends CoreException {
	
	private $msgId;
	
	private $msg;
	
	private $trace;
	
	/**
	 * Enter description here ...
	 * @param unknown_type $message
	 * @param unknown_type $code
	 * @param unknown_type $trace
	 */
	public function __construct($message, $code, $trace) {
		parent::__construct($message, $code, $trace);
		$this->msg = $message;
		$this->msgId = $code;
		$this->trace = $trace;
	}
}
?>