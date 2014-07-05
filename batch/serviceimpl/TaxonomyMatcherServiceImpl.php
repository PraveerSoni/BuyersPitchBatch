<?php
require_once (ROOT_PATH."batch/service/TaxonomyMatcherService.php");
require_once (ROOT_PATH."batch/serviceimpl/BatchDetailServiceImpl.php");
require_once (ROOT_PATH."batch/batchdaoimpl/BatchDataDaoImpl.php");

class TaxonomyMatcherServiceImp implements TaxonomyMatcherService {

	private $logger;

	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('TaxonomyMatcherServiceImp.php');
	}

	public function getCategoryHireracy($childCategoryId) {
		$dao = new TaxonomyDaoImpl();
		$parentCategoryIds = array();
		while(true) {
			$childCategoryId = $dao->findParentCategory($childCategoryId);
			if(null != $childCategoryId) {
				array_push($parentCategoryIds, $childCategoryId);
			} else {
				break;
			}
		}
		return $parentCategoryIds;
	}

	public function mapWithTaxonomyTable($string,$taxonomyCategoryObjArray=null) {
		$string = trim($string);
		if($string != ''){
			$service = new BatchDetailServiceImpl();
			$categoryIdArray = array();
			$productCategoryMapIdAddary = array();
			if(null != $taxonomyCategoryObjArray && isset($taxonomyCategoryObjArray) && is_array($taxonomyCategoryObjArray)) {
				$iLen = count($taxonomyCategoryObjArray);
				$this->logger->debug("Taxonomy Count:: ".$iLen);
				for($i = 0; $i < $iLen; $i++) {
					$product = $taxonomyCategoryObjArray[$i]->getProductIds();
					$cnt = count($product);
					$this->logger->debug("Input Product Count:: ".$cnt);
					if($cnt > 0) {
						for($k = 0; $k < $cnt; $k++){
							array_push($productCategoryMapIdAddary, $product[$k]);
						}
					}
					array_push($categoryIdArray, $taxonomyCategoryObjArray[$i]->getCategoryId());
				}
			}
			$productCategoryMapIds = $service->getProductMap($string, $categoryIdArray, $productCategoryMapIdAddary);
			$len = count($productCategoryMapIds);
			if($len == 0) {
				$categoryIds = $service->getCategory($string, $categoryIdArray);
				if(null != $categoryIds && isset($categoryIds) && is_array($categoryIds)) {
					$iCnt = count($taxonomyCategoryObjArray);
					$cnt = count($categoryIds);
					for($j = 0; $j < $cnt; $j++) {
						$found = 'N';
						for($i = 0; $i < $iCnt; $i++) {
							if($categoryIds[$i] == $taxonomyCategoryObjArray[$j]->getCategoryId()) {
								$found = 'Y';
								break;
							}
						}
						if($found == 'N') {
							$category = new Category($categoryIds[$i]);
							array_push($taxonomyCategoryObjArray, $category);
						}
					}
				}
			} else {
				$catLen = count($taxonomyCategoryObjArray);
				if($catLen > 0 && $iLen > 0) {
					for($i = 0; $i < $iLen; $i++) {
						$prdCat = $productCategoryMapIds[$i]->getCategoryId();
						for($j = 0; $j < $catLen; $j++) {
							$catCat = $taxonomyCategoryObjArray[$j]->getCategoryId();
							if($prdCat == $catCat) {
								array_splice($taxonomyCategoryObjArray, $j, 1);
							}
						}
					}
				}
				for($i = 0; $i < $len; $i++) {
					$taxonomyCategoryObjArray = $productCategoryMapIds[$i]->formCategoriesFromProductCategory($taxonomyCategoryObjArray);
				}
			}
		}
		return $taxonomyCategoryObjArray;
	}

	public function mapStringMatch(PostDetailsDTO $postDeatilsDTO) {

	}

	public function mapTaxonomyForInput(PostDetailsDTO $postDetailsDTO) {

		$batchDetailService = new BatchDetailServiceImpl();
		$taxonomyArray = $batchDetailService->getAllTaxonomyData();
		$taxonomy = $this->matchRequirementsForTaxonomy($postDetailsDTO->getPostText(), $taxonomyArray);
		$nonMatch = true;
		$this->logger->debug("Matching Taxonomy.. ".$taxonomy);
		if(null != $taxonomy) {
			$notificationArray = $batchDetailService->getAllNotificationInfo();
			$notCnt = count($notificationArray);
			$this->logger->debug("Notification Count:: ".$notCnt);
			if($notCnt > 0) {
				$notifyPostArray = $this->matchNotificationImproved($notificationArray, $taxonomy, $postDetailsDTO);
				if(null != $notifyPostArray && is_array($notifyPostArray) && isset($notifyPostArray)) {
					$cntNotifyPost = count($notifyPostArray);
					if($cntNotifyPost > 0) {
						$nonMatch = false;
						$this->logger->debug("Call Service to insert in DB for matching.. ".$cntNotifyPost);
						$batchDetailService->insertNotifyPost($notifyPostArray);
					}
				}
			}
		}
		if($nonMatch) {
			$this->logger->debug("Call Service to insert in DB for non matching.. ".$postDetailsDTO);
			$batchDetailService->insertNonNotifyPost($postDetailsDTO);
		}
	}

	private function matchNotification($notificationArray, $taxonomyArray, PostDetailsDTO $postDetailsDTO) {
		$cntNotification = count($notificationArray);
		$notifyPostArray = array();
		for($i = 0; $i < $cntNotification; $i++) {
			$add = 'Y';
			$notification = $notificationArray[$i];
			$notificationKeyWordsArray = BatchUtil::splitString(',', $notification->getKeywords());
			if(null != $notificationKeyWordsArray) {
				$cntTaxonomy = count($taxonomyArray);
				for($j = 0; $j < $cntTaxonomy; $j++) {
					$cntNotKeyWordsArray = count($notificationKeyWordsArray);
					for($k = 0; $k < $cntNotKeyWordsArray; $k++) {
						$spaceNotArray = BatchUtil::splitString(" ", $notificationKeyWordsArray[$k]);
						$pos = -1;
						if (null != $spaceNotArray && is_array($spaceNotArray)) {
							$spaceCnt = count($spaceNotArray);
							for($z = 0; $z < $spaceCnt; $z++) {
								$pos = strpos(strtoupper($taxonomyArray[$j]->getTaxonomyString()), strtoupper(ltrim(rtrim($spaceNotArray[$z]))));
								if($pos === false) {
									$add = 'N';
									break;
								}
							}
						} else {
							$pos = strpos(strtoupper($taxonomyArray[$j]->getTaxonomyString()), strtoupper(ltrim(rtrim($notificationKeyWordsArray[$k]))));
						}
						if($pos === false) {
							$add = 'N';
							break;
						}
					}
					if($add=='Y') {
						$this->logger->debug("Notification Match:: ".$notification);
						$this->logger->debug("Taxanomy Match:: ".$taxonomyArray[$j]);
						break;
					}
				}
				if($add == 'Y') {
					$notifyPost = new NotifyPost();
					$notifyPost->setPostId($postDetailsDTO->getPostDetailsId());
					$notifyPost->setRequirements($postDetailsDTO->getPostText());
					$notifyPost->setRequirementExpirationDateTime($postDetailsDTO->getPostEndDateTime());
					$notifyPost->setNotificationEmail($notification->getEmail());
					$notifyPost->setNotificationMobile($notification->getMobileNumber());
					$notifyPost->setNotificationKeyWords($notification->getKeywords());
					array_push($notifyPostArray, $notifyPost);
				}
			}
		}
		return $notifyPostArray;
	}


	private function matchNotificationImproved($notificationArray, Taxanomy $taxonomy, PostDetailsDTO $postDetailsDTO) {
		$cntNotification = count($notificationArray);
		$notifyPostArray = array();
		for($i = 0; $i < $cntNotification; $i++) {
			$add = 'N';
			$notification = $notificationArray[$i];
			//$this->logger->debug("Notification::: ".$notification);
			$notificationKeyWordsArray = BatchUtil::splitString(',', $notification->getKeywords());
			if(null != $notificationKeyWordsArray) {
				$cntNotKeyWordsArray = count($notificationKeyWordsArray);
				for($k = 0; $k < $cntNotKeyWordsArray; $k++) {
					$add = 'N';
					$taxanomyWordArray =  BatchUtil::splitString(',', $taxonomy->getTaxonomyString());
					$cntTaxonomyWord = count($taxanomyWordArray);
					for($z = 0; $z < $cntTaxonomyWord; $z++) {
						$this->logger->debug("notificationKeyWordsArray::: ".strtoupper($notificationKeyWordsArray[$k])." .... taxanomyWordArray.... ".strtoupper($taxanomyWordArray[$z]));
						if(strtoupper(ltrim(rtrim($notificationKeyWordsArray[$k]))) == strtoupper(ltrim(rtrim($taxanomyWordArray[$z])))) {
							$add = 'Y';
							break;
						}
					}
					$this->logger->debug("Flag to check:: ".$add);
					if($add=='N') {
						break;
					}
				}
			}
			if($add == 'Y') {
				$notifyPost = new NotifyPost();
				$notifyPost->setPostId($postDetailsDTO);
				$notifyPost->setNotificationEmail($notification->getEmail());
				$notifyPost->setNotificationMobile($notification->getMobileNumber());
				$notifyPost->setNotificationKeyWords($notification->getKeywords());
				$this->logger->debug("Match Add:: ".$notifyPost);
				array_push($notifyPostArray, $notifyPost);
			}
		}
		return $notifyPostArray;
	}
	private function matchRequirements($requirement, $taxonomyArray) {
		$matchTaxonomyArray = array();
		$newRequirement = BatchUtil::removeStopWords($requirement);
		$cnt = count($taxonomyArray);
		for ($i = 0; $i < $cnt; $i++) {
			$taxonomy = $taxonomyArray[$i];
			$taxonomyString = $taxonomy->getTaxonomyString();
			$status = $this->match($taxonomyString, $newRequirement);
			if($status == 'Y') {
				array_push($matchTaxonomyArray, $taxonomy);
			}
		}
		return $matchTaxonomyArray;
	}

	private function matchRequirementsForTaxonomy($requirement, $taxonomyArray) {
		$matchTaxonomy =null;
		$newRequirement = BatchUtil::removeStopWords($requirement);
		$cnt = count($taxonomyArray);
		for ($i = 0; $i < $cnt; $i++) {
			$taxonomy = $taxonomyArray[$i];
			$taxonomyString = $taxonomy->getTaxonomyString();
			$status = $this->match($taxonomyString, $newRequirement);
			if($status == 'Y') {
				$matchTaxonomy=$taxonomyArray[$i];
				break;
			}
		}
		return $matchTaxonomy;
	}

	private function match($commaSeperatedString, $text) {
		$array = explode(',', $commaSeperatedString);
		$cnt = count($array);
		for ($i = 0; $i < $cnt; $i++) {
			if(stristr($text, $array[$i]) == $array[$i]) {
				return 'Y';
			}
		}
	}

	private function matchNew($commaSeperatedString, $text) {
		$textArray = explode(' ', $commaSeperatedString);
		$array = explode(',', $commaSeperatedString);
		$cnt = count($array);
		for ($i = 0; $i < $cnt; $i++) {
			if(stristr($text, $array[$i]) == $array[$i]) {
				return 'Y';
			}
		}
	}

}
?>