<?php
require_once (ROOT_PATH."batch/serviceimpl/BatchDetailServiceImpl.php");
abstract class AbstractBatchStrategy {
	
	protected $logger;

	protected $batchDetail;
	
	public function __construct($batchTypeInputId) {
		$this->logger = BatchUtil::getBatchLogger('AbstractBatchStrategy.php');
		$this->batchDetail = new BatchDetail();
		$batchType = new BatchType();
		$batchType->setBatchTypeId($batchTypeInputId);
		$this->batchDetail->setBatchType($batchType);
		//$this->setBatchDetail($this->batchDetail);
	}

	public function setBatchDetail(BatchDetail $batchDetail) {
		$this->batchDetail = $batchDetail;
		
	}

	public function getBatchDetil() {
		return ($this->batchDetail);
	}

	protected final function createBatch() {
		$service = new BatchDetailServiceImpl();
		$this->batchDetail = $service->createBatch($this->batchDetail);
	}

	protected final function updateBatch($batchStatus) {
		$this->batchDetail->setBatchStatusId($batchStatus);
		$service = new BatchDetailServiceImpl();
		$service->updateBatch($this->batchDetail);
	}

	/**
	 * Enter description here ...
	 * @return boolean
	 */
	protected final function checkBatchRunningStatus() {
		$runningStatus = false;
		$service = new BatchDetailServiceImpl();
		$runningStatus = $service->checkBatchRunningStatus($this->batchDetail->getBatchType()->getBatchTypeId());
		//$runningStatus='N';
		$this->logger->debug("Status:: ".$runningStatus);
		return $runningStatus;
	}

	protected final function getBatchTypeInfo() {
		$this->logger->debug("getBatchTypeInfo start");
		$service = new BatchDetailServiceImpl();
		$batchType = $service->getBatchTypeById($this->batchDetail->getBatchType()->getBatchTypeId());
		$this->logger->debug("Batch Type:: ".$batchType);
		$this->batchDetail->setBatchType($batchType);
	}
}
?>