<?php
require_once (ROOT_PATH."batch/strategy/AbstractBatchStrategy.php");
require_once (ROOT_PATH."batch/strategy/BatchStrategy.php");
abstract class AbstractCustomeBatchStrategy extends AbstractBatchStrategy implements BatchStrategy {

	private $lastReadId;

	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	public function executeStrategy() {
		$batchStatus = 'success';
		try{
			$status = $this->checkBatchRunningStatus();
			$this->logger->debug("Status:: ".$status);
			if(strcasecmp($status, 'N') == 0) {
				$this->batchDetail->getBatchType()->setBatchRunStatus('Y');
				$dao = new BatchDetailDAOImpl();
				$dao->uppdateBatchType($this->batchDetail->getBatchType());
				$batchStatus = $this->doBatchProcessing();
				$this->batchDetail->getBatchType()->setBatchRunStatus('N');
				$dao->uppdateBatchType($this->batchDetail->getBatchType());
			}
		}catch (NoDataFoundException $e){
			$batchStatus = 'fail';
			$this->logger->error("Nodatfoundexception:: ".$e->getMessage(),$e);
			throw $e;
		} catch (Exception $e) {
			$this->logger->error("Exception:: ".$e->getMessage(),$e);
			$batchStatus = 'fail';
			$this->updateBatch($batchStatus);
			throw $e;
		}
		return ($batchStatus);
	}

	public function getLastReadId() {
		return ($this->lastReadId);
	}

	public function setLastReadId($id) {
		$this->lastReadId = $id;
	}

	/**
	 * Enter description here ...
	 */
	protected abstract function getData();

	/**
	 * Enter description here ...
	 * @param unknown_type $batchData
	*/
	protected abstract function processData($batchData);

	/**
	 * Enter description here ...
	*/
	protected final function updateLastReadId() {
		if(null != $this->lastReadId) {
			$service = new BatchDetailServiceImpl();
			$service->updateLastReadId($this->lastReadId, $this->getBatchDetil()->getBatchType()->getBatchTypeId());
		}
	}

	/**
	 * Enter description here ...
	 * @return unknown
	 */
	protected final function doBatchProcessing() {
		$this->logger->debug("Start Batch Processing Now");
		$this->getBatchTypeInfo();
		$batchDataArr = $this->getData();
		$this->createBatch();
		$status = $this->processData($batchDataArr);
		$this->updateLastReadId();
		if ($status == 'success') {
			$this->updateBatch(3);
		} else {
			$this->updateBatch(4);
		}
		$this->logger->debug("End Batch Processing Now");
		return ($status);
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $emailArray
	 * @param unknown_type $text
	 * @param unknown_type $email
	 */
	protected final function pushArray($emailArray, $text, $email) {
		$push = 'Y';
		$emailCnt = count($emailArray);
		$this->logger->debug("Count of Text for Email:: ".$email." is:: ".$emailCnt);
		for($j = 0; $j < $emailCnt; $j++) {
			$this->logger->debug("Text:: ".$text." length:: ".strlen($text));
			$this->logger->debug("Text in Email Array:: ".$emailArray[$j]." length:: ".strlen($emailArray[$j]));
			if($emailArray[$j] == $text) {
				$push = 'N';
				break;
			}
		}
		$this->logger->debug("Push Flag:: ".$push);
		if($push == 'Y') {
			array_push($emailArray, $text);
		}
		return $emailArray;
	}

	protected final function removeStopWords($string) {
		$string = BatchUtil::removeStopWords($string);
		return $string;
	}

	protected final function spliString($string, $delimeter) {
		$string = BatchUtil::splitString($delimeter, $string);
		return $string;
	}
}
?>