<?php
require_once (ROOT_PATH."batch/batchdto/BatchDetail.php");
interface BatchDataDaoFacade {
	
	function getBatchData(BatchType $batchType);
	
	function getAllNotification();
	
	function getAllMembers();
	
}
?>