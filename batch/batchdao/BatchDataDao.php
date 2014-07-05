<?php
require_once (ROOT_PATH."batch/batchdao/BatchDetailDAO.php");
interface BatchDataDao {

	function getNotifyPostBatchData(BatchType $batchType);

	function getNotifyBidBatchData(BatchType $batchType);

	function getAllNotificationData();

	function getAllUsers();

	function findAllTaxonomyData();

	function insertNotifyPost($notifyPostArray);

	function insertNonNotifyPost(PostDetailsDTO $postDetailsDto);

	function getTaxonomyForMatchData(BatchType $batchType);

	function getRequestNonTaxonomyData(BatchType $batchType);

	function getNotificationNonTaxonomyData(BatchType $batchType);

	function updatePostDetailWithCatAndProd($postDetails);
	
	function getRequestTaxonomy(BatchType $batchType);
	
	function insertNonNotifyPostArray($postDetailsDtos);

}
?>