<?php
class EmailBidNotification extends AbstractCustomeBatchStrategy {

	public function __construct($batchTypeInputId) {
		parent::__construct($batchTypeInputId);
	}

	protected function getData() {
		$service = new BatchDetailServiceImpl();
		$data = $service->getBatchDataForProcessing($this->batchDetail->getBatchType());
		$this->logger->debug("Bid Notification:: ".$data);
		$this->setLastReadId($data->getLastReadId());
		$this->logger->debug("Start Convert");
		$batchData = $this->convertData($data);
		$this->logger->debug("End Convert");
		return $batchData;
	}

	protected function processData($batchData) {
		$status="success";
		$this->logger->debug("Email Array:: ".$batchData);
		if(isset($batchData)) {
			$body=BatchUtil::getValueFromKey('NOTIFICATION_BID_BODY');
			$subject = BatchUtil::getValueFromKey('NOTIFICATION_BID_SUBJECT');
			foreach ($batchData as $key=>$value) {
				$bodyContext = array();
				$bodyTemplate = null;
				//$cnt = count($value);
				if(isset($value)) {
					$cnt = count($value);
					$bodyTemplate = "<p><ol>";
					for($j = 0; $j < $cnt; $j++){
						$pstArr = $value[$j];
						foreach($pstArr as $postKey=>$postValue) {
							$this->logger->debug("postKey:: ".$postKey);
							$pos = strpos($postKey, '_');
							$title = substr($postKey, $pos+1);
							$bodyTemplate = $bodyTemplate."<li>".$title."</li>";
							if(isset($postValue)) {
								$bodyTemplate = $bodyTemplate."<Table width=100% border=2>";
								$bodyTemplate = $bodyTemplate."<tr><td>Bid Description</td><td>Bid Amount</td></tr>";
								$bidCnt = count($postValue);
								$this->logger->debug("bid Count:: ".$bidCnt);
								for($i = 0 ; $i < $bidCnt; $i++) {
									$bid = $postValue[$i];
									$this->logger->debug("bid Det:: ".$bid);
									$bodyTemplate = $bodyTemplate."<tr><td>".$bid->getBidText()."</td><td>".$bid->getbidAmount()."</td></tr>";
								}
								$bodyTemplate = $bodyTemplate."</Table>";
							}
						}
					}
					$bodyTemplate = $bodyTemplate."</ol></p>";
				}
				array_push($bodyContext, $bodyTemplate);
				$this->logger->debug("Email To:: ".$key." subject:: ".$subject." body:: ".$body);
				$mailData = new EmailData($key, $subject, $body, true);
				BatchUtil::sendEmailFromTemplate($mailData, $bodyContext, null);
				$bodyContext = null;
			}
		}
		return $status;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private function convertData(NotifyBid $data) {
		$bidArray = $data->getBidArray();
		$emailDataArray = array();
		$emailArray = null;
		if(null != $bidArray && isset($bidArray) && is_array($bidArray)) {
			$cnt = count($bidArray);
			for($i = 0; $i < $cnt; $i++) {
				$bid = $bidArray[$i];
				$post = $bid->getPostId();
				$email = $post->getUserDetail()->getEmail();
				if(isset($emailDataArray[$email])) {
					$emailArray = $emailDataArray[$email];
				}
				else {
					$emailArray = array();
				}
				$this->logger->debug("pushBidDataArray start for email:: ".$email);
				$emailArray = $this->pushBidDataArray($emailArray, $post, $bid);
				$this->logger->debug("pushBidDataArray end for email:: ".$email);
				$emailDataArray[$email] = $emailArray;
			}
			foreach ($emailDataArray as $key=>$value) {
				$this->logger->debug("Key Email:: ".$key);
				$postArr = $value;
				$cnt = count($postArr);
				$this->logger->debug("Cnt:: "+$cnt);
			}
		}
		return $emailDataArray;
	}

	private function pushBidDataArray($emailArray, PostDetailsDTO $post, BidDetailDTO $bid) {
		$push = 'Y';
		$emailCnt = count($emailArray);
		$postArray = null;
		$this->logger->debug("New Count of Text for Email:: ".$emailCnt);
		for($j = 0; $j < $emailCnt; $j++)  {
			$breakFlag = 'N';
			$push = 'Y';
			$this->logger->debug("Post Id:: ".$post->getPostDetailsId());
			$postArray = $emailArray[$j];
			if(null != $postArray && isset($postArray)) {
				$postCnt = count($postArray);
				$this->logger->debug("Post Count:: ".$postCnt);
				foreach ($postArray as $key=>$value) {
					$this->logger->debug("Post Id + Title:: ".$key);
					$matchKey = $post->getPostDetailsId()."_".$post->getPostTitle();
					$this->logger->debug("Post Match Key:: ".$matchKey);
					if($key == $matchKey) {
						$push = 'N';
						$breakFlag = 'Y';
						$bidCnt = count($value);
						$this->logger->debug("Bid Array Count:: ".$bidCnt);
						$this->logger->debug("Match Key Bid:: ".$bid->getBidDetailId());
						if($bidCnt < 8) {
							for($k = 0; $k < $bidCnt; $k++){
								$this->logger->debug("Bid id in array:: ".$value[$k]->getBidDetailId());
								if($value[$k]->getBidDetailId() != $bid->getBidDetailId()) {
									array_push($value, $bid);
									$this->logger->debug("After Bid Array push Count:: ".count($value));
									$postArray[$matchKey]=$value;
									array_push($emailArray, $postArray);
								}
							}
						}
						break;
					}
				}
			}
			if($breakFlag == 'Y') {
				break;
			}
		}
		$this->logger->debug("Push Flag:: ".$push);
		if($push == 'Y') {
			$postArr = array();
			$bidArr = array();
			array_push($bidArr, $bid);
			$matchKey = $post->getPostDetailsId()."_".$post->getPostTitle();
			$postArr[$matchKey] = $bidArr;
			array_push($emailArray, $postArr);
		}
		return $emailArray;
	}
}
?>