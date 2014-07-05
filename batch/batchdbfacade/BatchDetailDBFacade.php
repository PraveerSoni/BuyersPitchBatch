<?php
interface BatchDetailDBFacade {
	
	function createBatchDetail(BatchDetail $batchDetail);
	
	function updateBatchDetail(BatchDetail $batchDetail);
	
	function updateLastReadId($id,$batchType);
	
	function getBatchTypeById($batchTypeId);
	
	function checkBatchRunningStatus($batchTypeId);
}
?>