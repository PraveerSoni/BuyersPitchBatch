<?php
require_once (ROOT_PATH."exception/CoreException.php");
class NoDataFoundException extends CoreException {
	private $msgId;
	private $msg;
	private $trace;

	/**
	 * Constructor.
	 * @param unknown_type $message
	 * @param unknown_type $code
	 * @param unknown_type $trace
	 */
	public function __construct ($message, $code, $trace) {
		parent::__construct($message, $code, $trace);
		$this->msgId=$code;
		$this->msg=$message;
		$this->trace=$trace;
	}
}
?>