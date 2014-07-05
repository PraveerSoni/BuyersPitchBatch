<?php
class EmailData {

	private $toEmailId;

	private $subject;

	private $body;

	private $msgType;

	public function __construct($emailId, $sub, $body, $msgType) {
		$this->toEmailId = $emailId;
		$this->subject = $sub;
		$this->body = $body;
		$this->msgType = $msgType;
	}

	public function getToEmailId() {
		return ($this->toEmailId);
	}

	public function getSubject() {
		return ($this->subject);
	}

	public function getBody() {
		return ($this->body);
	}

	public function getMsgType() {
		return ($this->msgType);
	}
}
?>