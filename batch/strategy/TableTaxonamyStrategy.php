<?php
require_once (ROOT_PATH.'batch/strategy/AbstractCustomBatchStrategy.php');
require_once (ROOT_PATH.'batch/serviceimpl/TaxonomyMatcherServiceImpl.php');
class TableTaxonamyStrategy extends AbstractCustomeBatchStrategy {
	
	private $notifiyPostMatchArray;
	
	private $notifiyPostNonMatchArray;
	
	private $notficationArray;
	
	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	protected function getData() {
		$service = new BatchDetailServiceImpl();
		$data = $service->getBatchDataForProcessing($this->batchDetail->getBatchType());
		$this->logger->debug("Taxonomy Notification:: ".$data);
		$iLen = count($data);
		return $data;
	}

	protected function processData($batchData) {
		$status="success";
		$cnt = count($batchData);
		$batchService = new BatchDetailServiceImpl();
		$service = new TaxonomyMatcherServiceImp();
		$this->notficationArray = $batchService->getAllNotificationInfoWithCatory();
		$this->notifiyPostMatchArray = array();
		$this->notifiyPostNonMatchArray = array();
		for($i = 0; $i< $cnt; $i++) {
			$notifyPost = $batchData[$i];
			$postDetailDto = $notifyPost->getPostId();
			$combined = $this->processMatch($postDetailDto);
			//$notifiyPostMatchArray = $combined[0];
			//$notifiyPostNonMatchArray = $combined[1];
			$this->setLastReadId($notifyPost->getNotifyPostId());
		}
		//$batchService = new BatchDetailServiceImpl();
		print_r($this->notifiyPostMatchArray);
		print_r($this->notifiyPostNonMatchArray);
		$batchService->insertNotifyPost($this->notifiyPostMatchArray);
		$batchService->insertNonNotifyPostArray($this->notifiyPostNonMatchArray);
		return $status;
	}

	private function processMatch(PostDetailsDTO $postDetailDto) {
		$postDetailDto->breakCategoryProduct();
		$nonMatch = "N";
		$combined = array();
		$reqCategoryArray = $postDetailDto->getCategoryId();
		if(count($reqCategoryArray) == 1) {
			$reqCategory = $reqCategoryArray[0];
			$matchExact = $this->exactMatch($reqCategory, $postDetailDto);
			$matchParent = $this->parentMatch($reqCategory, $postDetailDto);
			if($matchExact == "Y" || $matchParent == "Y") {
				$nonMatch = "Y";
			}
		}
		if($nonMatch == "N") {
			array_push($this->notifiyPostNonMatchArray, $postDetailDto);
		}
		//array_push($combined, $this->notifiyPostMatchArray);
		//array_push($combined, $notifiyPostNonMatchArray);
		return $combined;
	}

	private function parentMatch(Category $reqCategory,PostDetailsDTO $postDetailDto) {
		$service = new TaxonomyMatcherServiceImp();
		$parentCategoryIds = $service->getCategoryHireracy($reqCategory->getCategoryId());
		$iLen = count($this->notficationArray);
		$exist = "Y";
		foreach ($this->notficationArray as $notificationKey=>$notification) {
			//$notification = $this->notficationArray[$i];
			$notification->breakCategoryProduct();
			$notificationCategoryArray = $notification->getCategoryId();
			$found = "N";
			$notficationCategory = null;
			for($j = 0; $j < count($notificationCategoryArray); $j++) {
				$notficationCategory = $notificationCategoryArray[$j];
				for($j = 0; $j < count($parentCategoryIds); $j++) {
					if($parentCategoryIds[$j] == $notficationCategory->getCategoryId()) {
						$found = "Y";
						break;
					}
				}
			}
			if($found == "Y") {
				$prodMatch = $this->exactProductMatch($reqCategory, $notficationCategory);
				if($prodMatch == "Y") {
					$exist = "Y";
					$notifyPost = new NotifyPost();
					$notifyPost->setNotificationEmail($notification->getEmail());
					$notifyPost->setNotificationKeyWords($notification->getKeywords());
					$notifyPost->setNotificationMobile($notification->getMobileNumber());
					$notifyPost->setPostId($postDetailDto);
					array_push($this->notifiyPostMatchArray, $notifyPost);
					unset($this->notficationArray[$notificationKey]);
					//array_splice($this->notficationArray, $i, 1);
				}
			}
		}
		return $exist;
	}

	private function exactMatch(Category $reqCategory, PostDetailsDTO $postDetailDto) {
		$iLen = count($this->notficationArray);
		$this->logger->debug("notficationArray count :: ".$iLen);
		$exist = "N";
		foreach ($this->notficationArray as $notificationKey=>$notification) {
			//$notification = $this->notficationArray[$i];
			$notification->breakCategoryProduct();
			$notificationCategoryArray = $notification->getCategoryId();
			$found = "N";
			$notficationCategory = null;
			for($j = 0; $j < count($notificationCategoryArray); $j++) {
				$notficationCategory = $notificationCategoryArray[$j];
				if($reqCategory->getCategoryId() == $notficationCategory->getCategoryId()) {
					$found = "Y";
					break;
				}
			}
			if($found == "Y") {
				$prodMatch = $this->exactProductMatch($reqCategory, $notficationCategory);
				if($prodMatch == "Y") {
					$exist = "Y";
					$notifyPost = new NotifyPost();
					$notifyPost->setNotificationEmail($notification->getEmail());
					$notifyPost->setNotificationKeyWords($notification->getKeywords());
					$notifyPost->setNotificationMobile($notification->getMobileNumber());
					$notifyPost->setPostId($postDetailDto);
					array_push($this->notifiyPostMatchArray, $notifyPost);
					unset($this->notficationArray[$notificationKey]);
					//array_splice($this->notficationArray, $i, 1);
				}
			}
		}
		return $exist;
	}

	private function exactProductMatch(Category $reqCategory, Category $notificationCategory) {
		$reqProdIds = $reqCategory->getProductIds();
		$notficationProdIds = $notificationCategory->getProductIds();
		if(count($reqProdIds) > 0 && count($notficationProdIds)) {
			for($i = 0; $i < count($reqProdIds); $i++) {
				$found = "N";
				for($j = 0; $j < count($notficationProdIds); $j++) {
					if($reqProdIds[$i]->getProductId()== $notficationProdIds[$j]->getProductId()) {
						$found="Y";
						break;
					}
				}
				if($found == "N") {
					return "N";
				}
			}
		}
		return "Y";
	}
}
?>