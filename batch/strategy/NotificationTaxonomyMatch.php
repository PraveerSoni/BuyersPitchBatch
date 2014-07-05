<?php
require_once (ROOT_PATH.'domain/notificationMaster/NotificationDto.php');
require_once (ROOT_PATH.'batch/strategy/AbstractCustomBatchStrategy.php');
require_once (ROOT_PATH."batch/serviceimpl/TaxonomyMatcherServiceImpl.php");
require_once (ROOT_PATH."domain/taxonomy/TaxonomyProductCategory.php");
class NotificationTaxonomyMatch extends AbstractCustomeBatchStrategy {

	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	protected function getData() {
		$service = new BatchDetailServiceImpl();
		$data = $service->getBatchDataForProcessing($this->batchDetail->getBatchType());
		return $data;
	}

	protected function processData($batchData) {
		$ilen = count($batchData);
		$notificationDetailsArray = array();
		for($i = 0; $i < $ilen; $i++) {
			$notificationDetail = $this->matchNotificationTaxonomy($batchData[$i]->getNotificationDto());
			$this->logger->debug("Category Id for Notification:: ".$notificationDetail->getCategoryId());
			if(null != $notificationDetail->getCategoryId()) {
				array_push($notificationDetailsArray, $notificationDetail);
			}
			$this->setLastReadId($batchData[$i]->getId());
		}
		if(null != $notificationDetailsArray && isset($notificationDetailsArray) && is_array($notificationDetailsArray)) {
			$service = new BatchDetailServiceImpl();
			$service->updateNotifications($notificationDetailsArray);
		}
		return 'success';
	}

	public function matchNotificationTaxonomy(NotificationDto $notificationDto) {
		$this->logger->debug("Start matchReqTaxonomy");
		$requirements = $this->removeStopWords($notificationDto->getKeywords());
		$requirementsArray = $this->spliString($requirements, ",");
		$taxonomyService = new TaxonomyMatcherServiceImp();
		$ilen = count($requirementsArray);
		$categoryArray = array();
		for($i = 0; $i < $ilen; $i++) {
			$categoryTempArray = array();
			$string = $requirementsArray[$i];
			$strArray = $this->spliString($string, " ");
			if(count($strArray) > 1) {
				for($j = 0; $j < count($strArray); $j++) {
					$categoryTempArray = $taxonomyService->mapWithTaxonomyTable($strArray[$j], $categoryTempArray);
				}
			} else {
				$categoryTempArray = $taxonomyService->mapWithTaxonomyTable($string, $categoryTempArray);
			}
			$categoryArray = $this->mapCategoryToTempCategory($categoryArray,$categoryTempArray);
		}
		$this->logger->debug("Category Returned for post::: ".count($categoryArray));
		if(count($categoryArray) > 0) {
			$notificationDto->setCategoryId($categoryArray);
			$notificationDto->formCategoryProduct();
		}
		$this->logger->debug("Category Details:: ".$notificationDto->getCatIds());
		$this->logger->debug("Product Details:: ".$notificationDto->getProdIds());
		$this->logger->debug("End matchReqTaxonomy");
		return $notificationDto;
	}

	private function mapCategoryToTempCategory($categoryArray, $categoryTempArray) {
		$iLen = count($categoryArray);
		$iTempLen = count($categoryTempArray);
		if($iLen > 0) {
			for($i = 0; $i < $iTempLen; $i++) {
				$tempCategory = $categoryTempArray[$i];
				$found = "N";
				for($j = 0; $j < $iLen; $j++) {
					$category = $categoryArray[$j];
					if($category->getCategoryId()== $tempCategory->getCategoryId()) {
						$tempProductArray = $tempCategory->getProductIds();
						$prodLen = count($tempProductArray);
						for($k = 0; $k < $prodLen; $k++) {
							$category->setProductIds($tempProductArray[$k]->getProductId());
						}
						$this->logger->debug("Matched Category:: ".$category);
						$found = "Y";
						array_splice($categoryArray, $j,1);
						array_push($categoryArray, $category);
						break;
					}
				}
				if($found == "N") {
					array_push($categoryArray, $tempCategory);
				}
			}
		} else {
			if($iTempLen > 0) {
				$categoryArray = $categoryTempArray;
			}
		}
		return $categoryArray;
	}
}
?>