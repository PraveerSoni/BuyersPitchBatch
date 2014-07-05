<?php
define('ROOT_PATH',"C:/phpws/batch/");
require_once (ROOT_PATH.'batch/batchutil/GeneralBatchInclude.php');
require_once (ROOT_PATH.'cli/TaxonomyCli.php');
$logger = BatchUtil::getBatchLogger('Test.php');
$logger->debug("Start");
$mail = new SendMail('premprakashtewary@gmail.com', 'Test Mail', 'Test Mail Body', true);
$status = $mail->sendNonSSLMailUsingPhpMailer();
$logger->debug("End the Batch Process:: ".$status);
?>