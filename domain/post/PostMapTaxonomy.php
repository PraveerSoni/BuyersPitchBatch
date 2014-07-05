<?php
require_once (ROOT_PATH.'domain/post/PostDetails.php');
class PostMapTaxonomy {

	private $id;

	private $postDetailDto;

	private $notificationDto;

	public function __construct($pmtId, $postDet, $notification) {
		$this->id = $pmtId;
		$this->postDetailDto = $postDet;
		$this->notificationDto = $notification;
	}

	public function getId() {
		return $this->id;
	}

	public function getPostDetailDto() {
		return $this->postDetailDto;
	}

	public function getNotificationDto() {
		return $this->notificationDto;
	}
}
?>