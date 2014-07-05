<?php
require_once (ROOT_PATH.'batch/strategy/AbstractCustomBatchStrategy.php');
class EmailPostNotification extends AbstractCustomeBatchStrategy {

	private $notificationArray = null;
	private $userDetailArray = null;

	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	protected function getData() {
		$service = new BatchDetailServiceImpl();
		$data = $service->getBatchDataForProcessing($this->batchDetail->getBatchType());
		return $data;
	}

	protected function processData($batchData) {
		$status = 'success';
		try{
			$service = new BatchDetailServiceImpl();
			$cnt = count($batchData);
			$emailDataArray = $this->convertData($batchData);
			$this->sendMail($emailDataArray);
		}catch (NoDataFoundException $e) {
			$this->logger->error("No Data for execution:: ".$e->getMessage(),$e);
			$status = 'error';
		}
		catch (Exception $e) {
			$this->logger->error("Exception while execution:: ".$e->getMessage(),$e);
			$status = 'error';
		}
		return $status;
	}

	private function sendMail($emailDataArray) {
		$body=BatchUtil::getValueFromKey('NOTIFICATION_POST_BODY');
		$subject = BatchUtil::getValueFromKey('NOTIFICATION_POST_SUBJECT');
		foreach ($emailDataArray as $key=>$value) {
			$this->logger->debug("Key:: ".$key);
			$bodyContext = array();
			$bodyTemplate = null;
			if(isset($value)) {
				$cnt = count($value);
				$bodyTemplate = "<Table width=100% border=2>";
				$bodyTemplate = $bodyTemplate."<tr><td>Requirements</td><td>Requirement Expiration Date</td></tr>";
				for($i = 0; $i < $cnt; $i++) {
					$notifyPost = $value[$i];
					$bodyTemplate = $bodyTemplate."<tr><td>".$notifyPost->getPostId()->getPostText()."</td><td>".$notifyPost->getPostId()->getPostEndDateTime()."</td></tr>";
				}
				$bodyTemplate = $bodyTemplate."</Table>";
				$this->logger->debug("Body Template:: ".$bodyTemplate);
				array_push($bodyContext, $bodyTemplate);
				$this->logger->debug("Email To:: ".$key." subject:: ".$subject." body:: ".$body);
				$mailData = new EmailData($key, $subject, $body, true);
				BatchUtil::sendEmailFromTemplate($mailData, $bodyContext, null);
				$bodyContext = null;
			}
		}
	}

	private function convertData($notifyPostArray) {
		$emailDataArray = array();
		$emailArray = null;
		if(null != $notifyPostArray && isset($notifyPostArray) && is_array($notifyPostArray)) {
			$cnt = count($notifyPostArray);
			for($i = 0; $i < $cnt; $i++) {
				$notifyPost = $notifyPostArray[$i];
				$email = $notifyPost->getNotificationEmail();
				if(isset($emailDataArray[$email])) {
					$emailArray = $emailDataArray[$email];
				}
				else {
					$emailArray = array();
				}
				$emailArray = $this->pushPost($emailArray, $notifyPost);
				$emailDataArray[$email] = $emailArray;
				$this->setLastReadId($notifyPost->getNotifyPostId());
			}
		}
		return $emailDataArray;
	}

	private function pushPost($emailArray, NotifyPost $notifyPost) {
		$push = 'Y';
		$emailCnt = count($emailArray);
		for($i = 0; $i < $emailCnt; $i++) {
			$notPost = $emailArray[$i];
			if($notifyPost->getPostId()->getPostDetailsId() == $notPost->getPostId()->getPostDetailsId()) {
				$push = 'N';
				break;
			}
		}
		if($push == 'Y') {
			array_push($emailArray, $notifyPost);
		}
		return $emailArray;
	}
}
?>