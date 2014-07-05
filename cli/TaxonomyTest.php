<?php
define('ROOT_PATH',"C:/phpws/batch/");
//echo ROOT_PATH;
require_once (ROOT_PATH.'batch/batchutil/GeneralBatchInclude.php');
require_once (ROOT_PATH.'cli/TaxonomyCli.php');
$logger = BatchUtil::getBatchLogger('TaxonomyTest.php');
$logger->debug("Start");
$cli = new TaxonomyCli(null);
$status = $cli->run();
$logger->debug("End the Batch Process:: ".$status);
?>