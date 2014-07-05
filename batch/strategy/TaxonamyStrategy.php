<?php
require_once (ROOT_PATH.'batch/strategy/AbstractCustomBatchStrategy.php');
require_once (ROOT_PATH.'batch/serviceimpl/TaxonomyMatcherServiceImpl.php');
class TaxonamyStrategy extends AbstractCustomeBatchStrategy {

	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	protected function getData() {
		$service = new BatchDetailServiceImpl();
		$data = $service->getBatchDataForProcessing($this->batchDetail->getBatchType());
		$this->logger->debug("Taxonomy Notification:: ".$data);
		return $data;
	}

	protected function processData($batchData) {
		$status="success";
		$cnt = count($batchData);
		$service = new TaxonomyMatcherServiceImp();
		for($i = 0; $i< $cnt; $i++) {
			$notifyPost = $batchData[$i];
			$posteDetail = $notifyPost->getPostId();
			$service->mapTaxonomyForInput($posteDetail);
			$this->setLastReadId($notifyPost->getNotifyPostId());
		}
		return $status;
	}
}
?>