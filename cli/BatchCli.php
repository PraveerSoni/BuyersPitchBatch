<?php
require_once (ROOT_PATH.'batch/batchfactory/BatchFactory.php');
require_once (ROOT_PATH.'batch/batchdto/BatchDetail.php');
class BatchCli {

	private $batchTypeId;
	private $logger;

	public function __construct($argv) {
		$this->batchTypeId = $argv;
		$this->logger = BatchUtil::getBatchLogger('BatchCli.php');
	}

	public function run() {
		$this->logger->debug("Start run:: ".$this->batchTypeId);
		try{
			$batch = BatchFactory::getBatch($this->batchTypeId);
			$status = null;
			$status = $batch->executeStrategy();
		} catch (Exception $e) {
			$status = 'fail';
			$this->logger->error("Error While executing batch type:: ".$this->batchTypeId.". Error is:: ".$e->getMessage(),$e);
			throw $e;
		}
		return $status;
	}
}
?>