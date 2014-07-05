<?php
/**
 * Checked Exception Customized.
 * @author PREM
 *
 */
class CoreException extends Exception{
	private $msg;
	private $msgId;
	private $trace;

	/**
	 * Constructor.
	 * @param unknown_type $message
	 * @param unknown_type $code
	 * @param unknown_type $trace
	 */
	public function __construct ($message, $code, $trace) {
		parent::__construct($message, $code);
		$this->msgId=$code;
		$this->msg=$message;
		$this->trace=$trace;
	}
	
	public function getMsg() {
		return ($this->msg);
	}
	
	public function getMsgId() {
		return ($this->msgId);
	}
}
?>