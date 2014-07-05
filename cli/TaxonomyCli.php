<?php
require_once (ROOT_PATH.'batch/strategy/NotificationTaxonomyMatch.php');
require_once (ROOT_PATH."domain/notificationMaster/NotificationDto.php");
class TaxonomyCli {
	private $batchTypeId;
	private $logger;

	public function __construct($argv) {
		$this->batchTypeId = $argv;
		$this->logger = BatchUtil::getBatchLogger('TaxonomyCli.php');
	}

	public function run() {
		$this->logger->debug("Start run:: ");
		try{
			$batch = new NotificationTaxonomyMatch(5);
			$notification = new NotificationDto();
			$notification->setKeywords("Samsung Galaxy S4");
// 			$postDetailsDTO = new PostDetailsDTO();
// 			$postDetailsDTO->setPostDetailsId(1);
// 			$postDetailsDTO->setPostText("I want to buy Samsung Galaxy S3 mobile.");
			//$postDetailsDTO->setPostText("I want to buy Samsung Mobile");
			//$postDetailsDTO->setPostText("I want to buy Samsung Galaxy S3 Mobile");
			//$postDetailsDTO->setPostText("I want to buy India Gate Basmati Rice");
			//$postDetailsDTO->setPostText("I want to buy Kohinoor Basmati Rice");
			//$postDetailsDTO->setPostText("I want to buy Men Formal Shirt");
			//$postDetailsDTO->setPostText("I want to buy Men Formal Black Shoes");
			//$postDetailsDTO->setPostText("Need Software Developer with J2EE, PHP, MySQL experience");
			//$postDetailsDTO->setPostEndDateTime(date('Y-m-d H:i:s'));
			$status = null;
			//$this->logger->debug("postDetailsDTO....".$postDetailsDTO);
			//$status = $batch->matchReqTaxonomy($postDetailsDTO);
			$status = $batch->matchNotificationTaxonomy($notification);
		} catch (Exception $e) {
			$status = 'fail';
			$this->logger->error("Error While Taxonomy:: ".$e->getMessage(),$e);
		}
		return $status;
	}
}
?>