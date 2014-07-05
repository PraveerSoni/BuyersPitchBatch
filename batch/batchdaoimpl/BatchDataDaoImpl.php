<?php
require_once (ROOT_PATH."batch/batchdao/BatchDataDao.php");
require_once (ROOT_PATH."domain/post/PostDetails.php");
require_once (ROOT_PATH."domain/post/PostMapTaxonomy.php");
require_once (ROOT_PATH."domain/batchdomain/NotifyBid.php");
require_once (ROOT_PATH."domain/batchdomain/NotifyPost.php");
require_once (ROOT_PATH."persistence/AbstarctPDODB.php");
require_once (ROOT_PATH."domain/notificationMaster/NotificationDto.php");
require_once (ROOT_PATH."domain/taxonomy/Taxanomy.php");
class BatchDataDaoImpl extends AbstractPDODB implements BatchDataDao {

	private $logger;

	public function __construct() {
		$this->logger=BatchUtil::getBatchLogger('BatchDataDaoImpl.php');
	}

	public function getNotifyPostBatchData(BatchType $batchType) {
		$dbh = null;
		$postDetailArr=null;
		$lowerLimit = 0;
		$lastId = $batchType->getLastReadId();
		$upperLimit = $batchType->getBatchSize();
		try {
			$sql = BatchSQLQueryConstant::SQL_POST_NOTFICATION;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Post By Last id:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			/* $stmt->bindParam(2, $lowerLimit, PDO::PARAM_INT);
			 $stmt->bindParam(3, $upperLimit, PDO::PARAM_INT); */
			$postDetailArr = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$postDetail = new NotifyPost();
				$postDetail->setNotifyPostId($row->notify_post_id);
				$postDetail->setNotificationEmail($row->notification_email);
				$postDetail->setNotificationMobile($row->notification_mobile);
				$post = new PostDetailsDTO();
				$post->setPostDetailsId($row->post_id);
				$post->setPostText($row->requirements);
				$post->setPostEndDateTime($row->requirement_expiration_date);
				$postDetail->setPostId($post);
				// 				$postDetail->setPostId($row->post_id);
				// 				$postDetail->setRequirements($row->requirements);
				// 				$postDetail->setRequirementExpirationDateTime($row->requirement_expiration_date);
				array_push($postDetailArr, $postDetail);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getPostByPostId:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		if (null == $postDetailArr || !isset($postDetailArr) || !is_array($postDetailArr)) {
			throw new NoDataFoundException("No New Post for Notification batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		return ($postDetailArr);
	}

	public function getNotifyBidBatchData(BatchType $batchType) {
		$dbh = null;
		$bidDetail=null;
		$lowerLimit = 0;
		$lastId = $batchType->getLastReadId();
		$upperLimit = $batchType->getBatchSize();
		try {
			$sql = BatchSQLQueryConstant::SQL_BID_NOTIFICATION;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Post By Last id:: ".$sql);
			$this->logger->debug("last id:: ".$lastId);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			$bidDetailArr = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$lastId = $row->notify_bid_id;
				//Set Bid Detail
				$bid = new BidDetailDTO();
				$bid->setBidDetailId($row->bid_id);
				$bid->setBidAmount($row->bidamount);
				$bid->setBidText($row->description);
				//Set Bid User Detail
				// 				$userDetail = new UserDetail();
				// 				$userDetail->setUserId($row->bid_user_id);
				// 				$userDetail->setEmail($row->bid_email);
				// 				$userDetail->setContactNo($row->bid_mobile);
				// 				$userDetail->setFName($row->bidfname);
				// 				$userDetail->setLName($row->bidlname);
				// 				$bid->setUserDetailArr($userDetail);
				//Set Bid Post Details
				$post = new PostDetailsDTO();
				$post->setPostDetailsId($row->post_id);
				$post->setPostTitle($row->title);
				//Set Post User Details
				$userDetail = new UserDetail();
				$userDetail->setUserId($row->post_user_id);
				$userDetail->setEmail($row->post_email);
				$userDetail->setContactNo($row->post_mobile);
				$userDetail->setFName($row->postfname);
				$userDetail->setLName($row->postlname);
				$post->setUserDetail($userDetail);
				$bid->setPostId($post);
				array_push($bidDetailArr, $bid);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getPostByPostId:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		if (null == $bidDetailArr || !isset($bidDetailArr) || !is_array($bidDetailArr)) {
			throw new NoDataFoundException("No New Post for Notification batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		$bidDetail = new NotifyBid();
		$bidDetail->setLastReadId($lastId);
		$bidDetail->setBidArray($bidDetailArr);
		return ($bidDetail);
	}

	public function getAllNotificationData() {
		$dbh = null;
		$notificationArray=null;
		try {
			$sql = BatchSQLQueryConstant::SQL_NOTFICATION_NEW;
			$this->logger->debug("Sql for Notification:: ");
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$notificationArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$notification = new NotificationDto();
				$notification->setId($row->notification_id);
				$notification->setEmail($row->not_emailaddress);
				$notification->setMobileNumber($row->not_mobilenumber);
				$notification->setKeywords($row->keywords);
				array_push($notificationArray, $notification);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getPostByPostId:: ".$e->getMessage());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
			$dbh = null;
		}
		if (null == $notificationArray || !isset($notificationArray) || !is_array($notificationArray)) {
			throw new NoDataFoundException("No Notification:: ", 4, null);
		}
		return ($notificationArray);
	}

	public function getAllUsers(){
		$dbh = null;
		$notificationArray=null;
		$status = 1;
		try {
			$sql = BatchSQLQueryConstant::SQL_MEMBERS;
			$this->logger->debug("Sql for Members:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $status, PDO::PARAM_INT);
			$notificationArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$userDetail = new UserDetail();
				$userDetail->setUserId($row->id);
				$userDetail->setContactNo($row->mobilenumber);
				$userDetail->setEmail($row->emailaddress);
				$this->logger->debug("notification DTO :: ".$userDetail);
				array_push($notificationArray, $userDetail);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getAllMember:: ".$e->getMessage());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
			$dbh = null;
		}
		if (null == $notificationArray || !isset($notificationArray) || !is_array($notificationArray)) {
			throw new NoDataFoundException("No Member:: ", 4, null);
		}
		return ($notificationArray);

	}

	public function findAllTaxonomyData() {
		$dbh = null;
		$taxonomyArray=null;
		$status = 1;
		try {
			$sql = BatchSQLQueryConstant::SQL_ALL_TAXONOMY;
			$this->logger->debug("Sql for Members:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$taxonomyArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$taxonomy = new Taxanomy();
				$taxonomy->setAgeGroupTag($row->Age_Group_Tag);
				$taxonomy->setBrandTag($row->Brand_Tag);
				$taxonomy->setGenderTag($row->Gender_Tag);
				$taxonomy->setKeywords($row->Keywords);
				$taxonomy->setServiceNameTag($row->Services_Name);
				$taxonomy->setServiceTag($row->Services_Tag);
				$taxonomy->setServiceTypeTag($row->Services_Type);
				$taxonomy->setSynonym($row->synonym);
				$taxonomy->setTypeTag($row->Type_Tag);
				$this->logger->debug("taxonomy DTO :: ".$taxonomy);
				array_push($taxonomyArray, $taxonomy);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getAllMember:: ".$e->getMessage());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
			$dbh = null;
		}
		return ($taxonomyArray);
	}

	public function insertNotifyPost($notifyPostArray) {
		// TODO Need to iterate array and execute insert. Do Transaction Management
		$sql = BatchSQLQueryConstant::SQL_INS_NOTIFY_POST;
		$dbh = $this->formConnection();
		$dbh->beginTransaction();
		$cnt = count($notifyPostArray);
		for($i = 0; $i < $cnt; $i++) {
			$this->logger->debug("NotifyPost to insert:: ".$notifyPostArray[$i]);
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $notifyPostArray[$i]->getPostId()->getPostDetailsId(), PDO::PARAM_INT);
			$stmt->bindParam(2, $notifyPostArray[$i]->getPostId()->getPostText(),PDO::PARAM_STR);
			$stmt->bindParam(3, $notifyPostArray[$i]->getNotificationEmail(),PDO::PARAM_STR);
			$stmt->bindParam(4, $notifyPostArray[$i]->getNotificationMobile(),PDO::PARAM_STR);
			$stmt->bindParam(5, $notifyPostArray[$i]->getPostId()->getPostEndDateTime());
			$stmt->execute();
		}
		$dbh->commit();
		$dbh = null;
	}

	public function insertNonNotifyPost(PostDetailsDTO $postDetailsDto) {
		$sql = BatchSQLQueryConstant::SQL_INS_NOTIFY_POST_NON;
		$dbh = $this->formConnection();
		$dbh->beginTransaction();
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $postDetailsDto->getPostDetailsId());
		$stmt->bindParam(2, $postDetailsDto->getPostText());
		$stmt->execute();
		$id = $dbh->lastInsertId('Notify_Post_NonMatch_Id');
		$this->logger->debug("Inserted Notify_Post_NonMatch_Id:: ".$id);
		$dbh->commit();
	}

	public function insertNonNotifyPostArray($postDetailsDtos) {
		$sql = BatchSQLQueryConstant::SQL_INS_NOTIFY_POST_NON;
		$dbh = $this->formConnection();
		$dbh->beginTransaction();
		for($i = 0; $i < count($postDetailsDtos); $i++){
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $postDetailsDtos[$i]->getPostDetailsId());
			$stmt->bindParam(2, $postDetailsDtos[$i]->getPostText());
			$stmt->execute();
			$id = $dbh->lastInsertId('Notify_Post_NonMatch_Id');
			$this->logger->debug("Inserted Notify_Post_NonMatch_Id:: ".$id);
		}
		$dbh->commit();
	}

	public function getTaxonomyForMatchData(BatchType $batchType) {
		$dbh = null;
		$notificationPost=null;
		$lowerLimit = 0;
		$lastId = $batchType->getLastReadId();
		$upperLimit = $batchType->getBatchSize();
		try {
			$sql = BatchSQLQueryConstant::SQL_TAXONOMY_MATCH_GET;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Taxonomy Last id:: ".$sql);
			$this->logger->debug("last id:: ".$lastId);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			$notificationPostArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$notificationPost = new NotifyPost();
				$notificationPost->setNotifyPostId($row->notify_post_match_id);
				$postDetail = new PostDetailsDTO();
				$postDetail->setPostDetailsId($row->post_id);
				$postDetail->setPostText($row->requirements);
				$postDetail->setPostEndDateTime($row->requirement_expiration_date);
				$notificationPost->setPostId($postDetail);
				array_push($notificationPostArray, $notificationPost);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getting Data For Taxonomy Match:: ".$e->getMessage());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
			$dbh = null;
		}
		if (null == $notificationPostArray || !isset($notificationPostArray) || !is_array($notificationPostArray)) {
			throw new NoDataFoundException("No New Post for Taxonomy Match batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		return ($notificationPostArray);
	}

	public function getRequestNonTaxonomyData(BatchType $batchType) {
		$dbh = null;
		$requestNonTaxonomyArray=null;
		$lowerLimit = 0;
		$upperLimit = $batchType->getBatchSize();
		$lastId = $batchType->getLastReadId();
		try {
			$sql = BatchSQLQueryConstant::SQL_TAXONOMY_NON_REQ;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Request Non Taxonomy Match:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			$requestNonTaxonomyArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$postDetail = new PostDetailsDTO();
				$postDetail->setPostDetailsId($row->id);
				$postDetail->setPostText($row->requirements);
				$postDetail->setPostEndDateTime($row->ExpireDateTime);
				$pmtDeto = new PostMapTaxonomy($row->rmtid, $postDetail,null);
				array_push($requestNonTaxonomyArray, $pmtDeto);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$dbh = null;
			$this->logger->error("Error While getting Post Data For Taxonomy Match:: ".$e->getMessage()." The Batch Running is:: ".$batchType->getBatchTypeName());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		if (null == $requestNonTaxonomyArray || !isset($requestNonTaxonomyArray) || !is_array($requestNonTaxonomyArray)) {
			throw new NoDataFoundException("No New Post for Taxonomy Match batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		return ($requestNonTaxonomyArray);
	}

	public function getNotificationNonTaxonomyData(BatchType $batchType) {
		$dbh = null;
		$requestNonTaxonomyArray=null;
		$lowerLimit = 0;
		$upperLimit = $batchType->getBatchSize();
		$lastId = $batchType->getLastReadId();
		try {
			$sql = BatchSQLQueryConstant::SQL_TAXONOMY_NON_NOTIFY;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Request Non Taxonomy Match:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			$requestNonTaxonomyArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$notification = new NotificationDto();
				$notification->setId($row->id);
				$notification->setKeywords($row->keywords);
				$pmtDeto = new PostMapTaxonomy($row->rmtid, null,$notification);
				array_push($requestNonTaxonomyArray, $pmtDeto);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getting Notification For Taxonomy Match:: ".$e->getMessage()." The Batch Running is:: ".$batchType->getBatchTypeName());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		if (null == $requestNonTaxonomyArray || !isset($requestNonTaxonomyArray) || !is_array($requestNonTaxonomyArray)) {
			throw new NoDataFoundException("No New Post for Taxonomy Match batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		return ($requestNonTaxonomyArray);
	}

	public function updatePostDetailWithCatAndProd($postDetails) {
		$sql = BatchSQLQueryConstant::UPDATE_REQ_WITH_CAT_PROD;
		$dbh = $this->formConnection();
		$sql2 = BatchSQLQueryConstant::INS_NOTIFY_POST_MATCH;
		try{
			$dbh->beginTransaction();
			$cnt = count($postDetails);
			for($i = 0; $i < $cnt; $i++) {
				$this->logger->debug("Post to Update:: ".$postDetails[$i]);
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(1, $postDetails[$i]->getCatIds(), PDO::PARAM_STR);
				$stmt->bindParam(2, $postDetails[$i]->getProdIds(),PDO::PARAM_STR);
				$stmt->bindParam(3, $postDetails[$i]->getPostDetailsId(),PDO::PARAM_INT);
				$stmt->execute();
				$stmt = $dbh->prepare($sql2);
				$stmt->bindParam(1, $postDetails[$i]->getPostDetailsId(), PDO::PARAM_STR);
				$stmt->bindParam(2, $postDetails[$i]->getPostText(),PDO::PARAM_STR);
				$stmt->bindParam(3, $postDetails[$i]->getPostEndDateTime(),PDO::PARAM_INT);
				$stmt->execute();
			}
			$dbh->commit();
			$dbh = null;
		}catch (PDOException $e) {
			$dbh->rollback();
			$this->logger->error("Error While Updating Post Data:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function updateupdateNotificationslWithCatAndProd($notifications) {
		$sql = BatchSQLQueryConstant::UPDATE_NOTFICATION_WITH_CAT_PROD;
		$dbh = $this->formConnection();
		try{
			$dbh->beginTransaction();
			$cnt = count($notifications);
			for($i = 0; $i < $cnt; $i++) {
				$this->logger->debug("Notification to Update:: ".$notifications[$i]);
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(1, $notifications[$i]->getCatIds(), PDO::PARAM_STR);
				$stmt->bindParam(2, $notifications[$i]->getProdIds(),PDO::PARAM_STR);
				$stmt->bindParam(3, $notifications[$i]->getId(),PDO::PARAM_INT);
				$stmt->execute();
			}
			$dbh->commit();
			$dbh = null;
		}catch (PDOException $e) {
			$dbh->rollback();
			$this->logger->error("Error While Updating Post Data:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function getRequestTaxonomy(BatchType $batchType) {
		$dbh = null;
		$requestNonTaxonomyArray=null;
		$lowerLimit = 0;
		$upperLimit = $batchType->getBatchSize();
		$lastId = $batchType->getLastReadId();
		try {
			$sql = BatchSQLQueryConstant::SQL_TAXONOMY_REQ;
			$sql=$sql." LIMIT 0,".$upperLimit;
			$this->logger->debug("Sql for Request Taxonomy Match:: ".$sql);
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $lastId, PDO::PARAM_INT);
			$requestTaxonomyArray = array();
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$notifyPost = new NotifyPost();
				$postDetail = new PostDetailsDTO();
				$postDetail->setPostDetailsId($row->id);
				$postDetail->setPostText($row->requirements);
				$postDetail->setPostEndDateTime($row->ExpireDateTime);
				$postDetail->setCatIds($row->category_id);
				$postDetail->setProdIds($row->product_category_map_id);
				$notifyPost->setPostId($postDetail);
				$notifyPost->setNotifyPostId($row->notify_post_match_id);
				array_push($requestTaxonomyArray, $notifyPost);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While getting Post Data For Taxonomy Match:: ".$e->getMessage());
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
			$dbh = null;
		}
		if (null == $requestTaxonomyArray || !isset($requestTaxonomyArray) || !is_array($requestTaxonomyArray)) {
			throw new NoDataFoundException("No New Post for Taxonomy Match batch:: ".$batchType->getBatchTypeId()." and last read id:: ".$batchType->getLastReadId(), 4, null);
		}
		return ($requestTaxonomyArray);
	}
}
?>