<?php
class BatchType {

	private $batchTypeId;

	private $batchTypeName;

	private $batchDesc;

	private $batchSize;

	private $lastReadId;
	
	private $runStatus;

	public function __construct() {

	}

	public function setBatchTypeId($batchTypeId) {
		$this->batchTypeId = $batchTypeId;
	}

	public function getBatchTypeId() {
		return ($this->batchTypeId);
	}

	public function setBatchTypeName($batchTypeName) {
		$this->batchTypeName = $batchTypeName;
	}

	public function getBatchTypeName() {
		return ($this->batchTypeName);
	}

	public function setBatchDesc($batchDesc) {
		$this->batchDesc = $batchDesc;
	}

	public function getBtachDesc() {
		return ($this->batchDesc);
	}

	public function setBatchSize($batchSize) {
		$this->batchSize = $batchSize;
	}

	public function getBatchSize() {
		return ($this->batchSize);
	}

	public function getLastReadId() {
		return ($this->lastReadId);
	}

	public function setLastReadId($lastReadId) {
		$this->lastReadId = $lastReadId;
	}
	
	public function setBatchRunStatus($runStatus) {
		$this->runStatus = $runStatus;
	}
	
	public function getBatchRunStatus() {
		return $this->runStatus;
	}
	
	public function __toString() {
		$stringValue="[Batch Type=".$this->batchTypeId."]";
		$stringValue=$stringValue."[Batch Size=".$this->batchSize."]";
		return ($stringValue);
	}
}
?>