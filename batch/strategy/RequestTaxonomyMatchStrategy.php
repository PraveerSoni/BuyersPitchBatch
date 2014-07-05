<?php
require_once (ROOT_PATH.'batch/strategy/AbstractCustomBatchStrategy.php');
require_once (ROOT_PATH."batch/serviceimpl/TaxonomyMatcherServiceImpl.php");
require_once (ROOT_PATH."domain/taxonomy/TaxonomyProductCategory.php");
class RequestTaxonomyMatchStrategy extends AbstractCustomeBatchStrategy {

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
		$postDetailsArray = array();
		for($i = 0; $i < $ilen; $i++) {
			$postDetails = $this->matchReqTaxonomy($batchData[$i]->getPostDetailDto());
			if(null != $postDetails->getCategoryId()) {
				array_push($postDetailsArray, $postDetails);
			} else {
				$service = new BatchDetailServiceImpl();
				$service->insertNonNotifyPost($postDetails);
			}
			$this->setLastReadId($batchData[$i]->getId());
		}
		if(null != $postDetailsArray && isset($postDetailsArray) && is_array($postDetailsArray)) {
			$service = new BatchDetailServiceImpl();
			$service->updatePost($postDetailsArray);
		}
		return 'success';
	}

	public function matchReqTaxonomy(PostDetailsDTO $postDetailsDto) {
		$this->logger->debug("Start matchReqTaxonomy");
		$requirements = $this->removeStopWords($postDetailsDto->getPostText());
		$requirementsArray = $this->spliString($requirements, " ");
		$taxonomyService = new TaxonomyMatcherServiceImp();
		$ilen = count($requirementsArray);
		$categoryArray = array();
		for($i = 0; $i < $ilen; $i++) {
			$string = $requirementsArray[$i];
			$categoryArray = $taxonomyService->mapWithTaxonomyTable($string, $categoryArray);
		}
		$this->logger->debug("Category Returned for post::: ".count($categoryArray));
		if(count($categoryArray) > 0) {
			$postDetailsDto->setCategoryId($categoryArray);
			$postDetailsDto->formCategoryProduct();
		}
		$this->logger->debug("Category Details:: ".$postDetailsDto->getCatIds());
		$this->logger->debug("Product Details:: ".$postDetailsDto->getProdIds());
		$this->logger->debug("End matchReqTaxonomy");
		return $postDetailsDto;
	}
}
?>