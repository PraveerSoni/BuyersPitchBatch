<?php
require_once (ROOT_PATH."domain/post/PostDetails.php");
class NotifyPost {

	private $notifyPostId;

	private $postDetails;

	private $notificationEmail;

	private $notificationMobile;

	private $notificationKeyWords;

	public function __construct() {

	}

	public function setNotifyPostId($notPostId) {
		$this->notifyPostId = $notPostId;
	}

	public function getNotifyPostId() {
		return $this->notifyPostId;
	}

	public function setPostId(PostDetailsDTO $postDetails) {
		$this->postDetails = $postDetails;
	}

	public function getPostId() {
		return $this->postDetails;
	}

	public function setNotificationEmail($notEmail) {
		$this->notificationEmail = $notEmail;
	}

	public function getNotificationEmail() {
		return $this->notificationEmail;
	}

	public function setNotificationMobile($notMobile) {
		$this->notificationMobile = $notMobile;
	}

	public function getNotificationMobile() {
		return $this->notificationMobile;
	}

	public function setNotificationKeyWords($keywords) {
		$this->notificationKeyWords = $keywords;
	}

	public function getNotificationKeyWords() {
		return $this->notificationKeyWords;
	}

	public function __toString() {
		$obj = 'NotifyPost{ notifyPostid='.$this->notifyPostId;
		$obj = $obj.' postDetails='.$this->postDetails;
		$obj = $obj.' notificationEmail='.$this->notificationEmail;
		if(null != $this->notificationKeyWords  && is_string($this->notificationKeyWords)) {
			$obj = $obj.' notificationKeyWords='.$this->notificationKeyWords;
		}
		$obj = $obj.' }';
		return $obj;
	}
}
?>