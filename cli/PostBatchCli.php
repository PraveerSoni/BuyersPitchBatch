<?php
define('ROOT_PATH',"C:/phpws/batch/");
require_once (ROOT_PATH.'batch/batchutil/GeneralBatchInclude.php');
require_once (ROOT_PATH.'cli/BatchCli.php');
$logger = BatchUtil::getBatchLogger('PostBatchCli.php');
$logger->debug("Start");
$logger->debug("Args:: ".$argv[0]);
$logger->debug("Args1:: ".$argv[1]);
$cli = new BatchCli($argv[1]);
try{
	$status = $cli->run();
	$logger->debug("End the Batch Process:: ".$status);
}catch (Exception $e) {
	$batchType = new BatchType();
	$batchType->setBatchTypeId($argv[1]);
	$batchType->setBatchRunStatus('N');
	$dao = new BatchDetailDAOImpl();
	$dao->uppdateBatchType($batchType);
}
?>