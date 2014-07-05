<?php
require_once (ROOT_PATH."batch/batchdbfacade/BatchDetailDBFacade.php");
require_once (ROOT_PATH."batch/batchdaoimpl/BatchDetailDAOImpl.php");
class BatchDetailDBFacadeImpl implements BatchDetailDBFacade {

	private $logger;

	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('BatchDetailDBFacadeImpl.php');
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDBFacade::createBatchDetail()
	 */
	public function createBatchDetail(BatchDetail $batchDetail) {
		$dao = new BatchDetailDAOImpl();
		$batchDetail = $dao->createBatchDetail($batchDetail);
		return ($batchDetail);
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDBFacade::updateBatchDetail()
	 */
	public function updateBatchDetail(BatchDetail $batchDetail) {
		$dao = new BatchDetailDAOImpl();
		$dao->updateBatchDetail($batchDetail);
	}
	
	/* (non-PHPdoc)
	 * @see BatchDetailDBFacade::updateLastReadId()
	 */
	public function updateLastReadId($id, $batchType) {
		$dao = new BatchDetailDAOImpl();
		$dao->updateLastId($id, $batchType);
	}
	
	/* (non-PHPdoc)
	 * @see BatchDetailDBFacade::getBatchTypeById()
	 */
	public function getBatchTypeById($batchTypeId) {
		$dao = new BatchDetailDAOImpl();
		$batchType = $dao->getBatchTypeDetails($batchTypeId);
		return $batchType;
	}
	
	/* (non-PHPdoc)
	 * @see BatchDetailDBFacade::checkBatchRunningStatus()
	 */
	public function checkBatchRunningStatus($batchTypeId){
		$dao = new BatchDetailDAOImpl();
		$status = $dao->checkBatchRunningStatus($batchTypeId);
		return $status;
	}
	
}
?>