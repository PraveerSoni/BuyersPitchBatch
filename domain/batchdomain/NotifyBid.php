<?php
require_once (ROOT_PATH."domain/Bid/BidDetailDTO.php");
class NotifyBid {

	private $lastReadId;

	private $bidArray;

	public function __construct() {

	}

	public function setLastReadId($lastId) {
		$this->lastReadId = $lastId;
	}

	public function getLastReadId() {
		return $this->lastReadId;
	}

	public function setBidArray($bidDetailArray) {
		$this->bidArray = $bidDetailArray;
	}

	public function getBidArray() {
		return $this->bidArray;
	}

	public function  __toString() {
		$stringValue = "[NotifyBid= ";
		$stringValue = $stringValue."[lastReadId=".$this->lastReadId."]";
		if(null != $this->bidArray && isset($this->bidArray) && is_array($this->bidArray)) {
			$cnt = count($this->bidArray);
			for($i = 0; $i < $cnt; $i++) {
				$stringValue = $stringValue.$this->bidArray[$i];
			}
		}
		$stringValue = $stringValue."]";
		return $stringValue;
	}
}
?>