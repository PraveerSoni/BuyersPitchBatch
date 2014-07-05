<?php
require_once (ROOT_PATH."exception/CoreRuntimeException.php");

/**
 * Runtime Internal Exception.
 * @author PREM
 *
 */
class InternalException extends CoreRuntimeException {

	public function __construct ($message, $code, $trace) {
		parent::__construct($message, $code, $trace);
	}
}
?>