<?php
interface BatchService {

	function createBatch(BatchDetail $batchDetail);

	function updateBatch(BatchDetail $batchDetail);

	function updateLastReadId($id, $batchType);

	function getBatchTypeById($batchTypeId);

	function checkBatchRunningStatus($batchTypeId);

	function getBatchDataForProcessing(BatchType $batchType);

	function getAllNotificationInfo();

	function getAllMembers();

	function getAllTaxonomyData();

	function insertNotifyPost($notifyPostArray);

	function insertNonNotifyPost(PostDetailsDTO $postDetailsDto);

	function getProductMap($string,$categoryIdArray=null,$productCategoryMapIdAddary = null);

	function getCategory($string, $categoryIdArray=null);

	function updatePost($postDetails);

	function updateNotifications($notificationDetails);

	function getAllNotificationInfoWithCatory();

	function insertNonNotifyPostArray($postDetailsDtos);
}
?>