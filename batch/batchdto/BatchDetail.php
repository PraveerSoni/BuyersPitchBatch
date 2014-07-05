<?php
require_once (ROOT_PATH.'batch/batchdto/BatchType.php');
class BatchDetail {

	private $batchDetailId;

	private $batchType;

	private $batchStatusId;

	private $batchRunningStatus;
	

	public function __construct() {

	}

	public function  __destruct() {

	}

	public function setBatchDetailId($batchDetailId) {
		$this->batchDetailId = $batchDetailId;
	}

	public function getBatchDetailId() {
		return ($this->batchDetailId);
	}

	public function setBatchType(BatchType $batchType) {
		$this->batchType = $batchType;
	}

	public function getBatchType() {
		return ($this->batchType);
	}

	public function setBatchStatusId($batchStatusId) {
		$this->batchStatusId = $batchStatusId;
	}

	public function getBatchStatusId() {
		return ($this->batchStatusId);
	}

	public function setBatchRunningStatus($batchRunStatus) {
		$this->batchRunningStatus = $batchRunStatus;
	}

	public function getBatchRunningStatus() {
		return  ($this->batchRunningStatus);
	}
}
?>