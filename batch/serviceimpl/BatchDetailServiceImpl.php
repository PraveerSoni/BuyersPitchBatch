<?php
require_once (ROOT_PATH."batch/service/BatchDetailService.php");
require_once (ROOT_PATH."batch/batchdbfacadeimpl/BatchDetailDBFacadeImpl.php");
require_once (ROOT_PATH.'batch/batchdbfacadeimpl/BatchDataDaoFacadeImpl.php');
require_once (ROOT_PATH.'batch/batchdaoimpl/TaxonomyDaoImpl.php');
class BatchDetailServiceImpl implements BatchService {

	private $logger;

	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('BatchDetailServiceImpl.php');
	}

	public function createBatch(BatchDetail $batchDetail) {
		$dbFacade = new BatchDetailDBFacadeImpl();
		$batchDetail = $dbFacade->createBatchDetail($batchDetail);
		return ($batchDetail);
	}

	public function updateBatch(BatchDetail $batchDetail) {
		$dbFacade = new BatchDetailDBFacadeImpl();
		$dbFacade->updateBatchDetail($batchDetail);
	}

	public function updateLastReadId($id, $batchType) {
		$dbfacade = new BatchDetailDBFacadeImpl();
		$dbfacade->updateLastReadId($id, $batchType);
	}

	public function getBatchTypeById($batchTypeId) {
		$dbfacade = new BatchDetailDBFacadeImpl();
		$batchType = $dbfacade->getBatchTypeById($batchTypeId);
		return $batchType;
	}

	/* (non-PHPdoc)
	 * @see BatchService::checkBatchRunningStatus()
	*/
	public function checkBatchRunningStatus($batchTypeId){
		$dbfacade = new BatchDetailDBFacadeImpl();
		$status = $dbfacade->checkBatchRunningStatus($batchTypeId);
		$this->logger->debug("Service status:: ".$status);
		return $status;
	}

	/* (non-PHPdoc)
	 * @see BatchService::getBatchDataForProcessing()
	*/
	public function getBatchDataForProcessing(BatchType $batchType) {
		$dbFacade = new BatchDataDaoFacadeImpl();
		$array = $dbFacade->getBatchData($batchType);
		return $array;
	}

	public function getAllNotificationInfo() {
		$dbFacade = new BatchDataDaoFacadeImpl();
		$notificationArray = $dbFacade->getAllNotification();
		return $notificationArray;
	}

	public function getAllMembers() {
		$dbFacade = new BatchDataDaoFacadeImpl();
		$userDetailArray = $dbFacade->getAllMembers();
		return $userDetailArray;
	}

	public function getAllTaxonomyData() {
		$dao = new BatchDataDaoImpl();
		$taxonomyArray = $dao->findAllTaxonomyData();
		if(null == $taxonomyArray || !isset($taxonomyArray) || !is_array($taxonomyArray)) {
			throw new NoDataFoundException("No Data Exists for Taxonomy", 404, null);
		}
		return $taxonomyArray;
	}

	public function insertNotifyPost($notifyPostArray) {
		$dao = new BatchDataDaoImpl();
		$dao->insertNotifyPost($notifyPostArray);
	}

	public function insertNonNotifyPost(PostDetailsDTO $postDetailsDto) {
		$dao = new BatchDataDaoImpl();
		$dao->insertNonNotifyPost($postDetailsDto);
	}
	
	public function insertNonNotifyPostArray($postDetailsDtos) {
		$dao = new BatchDataDaoImpl();
		$dao->insertNonNotifyPostArray($postDetailsDtos);
	}

	public function getProductMap($string,$categoryIdArray=null,$productCategoryMapIdArray = null) {
		$dao = new TaxonomyDaoImpl();
		$prodIds = $dao->findProductMap(strtoupper($string), $categoryIdArray, $productCategoryMapIdArray);
		if(count($prodIds) == 0) {
			$prodIds = $dao->findProductSynonnymMap(strtoupper($string), $categoryIdArray, $productCategoryMapIdArray);
		}
		return $prodIds;
	}

	public function getCategory($string, $categoryIdArray=null) {
		$dao = new TaxonomyDaoImpl();
		$catIds = $dao->findCategory(strtoupper($string), $categoryIdArray);
		if(null == $catIds && !isset($catIds) && !is_array($catIds)) {
			$catIds = $dao->findCategorySynonym(strtoupper($string), $categoryIdArray);
		}
		return $catIds;
	}

	public function updatePost($postDetails) {
		$dao = new BatchDataDaoImpl();
		$dao->updatePostDetailWithCatAndProd($postDetails);
	}

	public function updateNotifications($notifications) {
		$dao = new BatchDataDaoImpl();
		$dao->updateupdateNotificationslWithCatAndProd($notifications);
	}

	public function getAllNotificationInfoWithCatory() {
		$dao = new TaxonomyDaoImpl();
		$notificationArray = $dao->findAllNotificationWithCategory();
		$iLen = count($notificationArray);
		if($iLen == 0) {
			throw new NoDataFoundException("No Notification with Category value", 4);
		}
		return $notificationArray;
	}
}
?>