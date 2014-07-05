<?php
require_once (ROOT_PATH."batch/batchdbfacade/BatchDataDaoFacade.php");
require_once (ROOT_PATH."batch/batchdaoimpl/BatchDataDaoImpl.php");
class BatchDataDaoFacadeImpl implements BatchDataDaoFacade {

	private $logger;

	public function __construct() {
		$this->logger=BatchUtil::getBatchLogger('BatchDataDaoFacadeimpl.php');
	}

	public function getBatchData(BatchType $batchType) {
		$this->logger->debug("To get Batch Data Batch Type Id:: ".$batchType->getBatchTypeId());
		$dao = new BatchDataDaoImpl();
		$array = null;
		switch ($batchType->getBatchTypeId()) {
			case 1:
				$array = $dao->getNotifyPostBatchData($batchType);
				break;
			case 2:
				$array = $dao->getNotifyBidBatchData($batchType);
				break;
			case 3:
				$array = $dao->getTaxonomyForMatchData($batchType);
				break;
			case 4:
				$array = $dao->getRequestNonTaxonomyData($batchType);
				break;
			case 5:
				$array = $dao->getNotificationNonTaxonomyData($batchType);
				break;
			case 6:
				$array = $dao->getRequestTaxonomy($batchType);
				break;
			default:
				throw new NoDataFoundException("No Batch Type for the Given Batch Type:: ".$batchType->getBatchTypeId(), 4, null);
				break;
		}
		return $array;
	}

	public function getAllNotification() {
		$dao = new BatchDataDaoImpl();
		$notificationArray = $dao->getAllNotificationData();
		return $notificationArray;
	}

	public function getAllMembers() {
		$dao = new BatchDataDaoImpl();
		$userDetailArray = $dao->getAllUsers();
		return $userDetailArray;
	}
}
?>