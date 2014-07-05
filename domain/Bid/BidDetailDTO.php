<?php
require_once (ROOT_PATH."domain/userdetail/UserDetail.php");
require_once (ROOT_PATH."domain/post/PostDetails.php");
require_once (ROOT_PATH."domain/ImageDetail.php");
/**
 * Enter description here ...
 * @author PREM
 *
 */
class BidDetailDTO {

	private $bidDetailId;

	private $userDetailArr;

	private $bidStarDateTime;

	private $isActive;

	private $bidAmount;

	private $bidUpdateDateTime;

	private $isClosed;

	private $bidText;

	private $postId;

	private $intrested;
	
	private $imageArray;

	public function __construct() {

	}

	public function setBidDetailId($bidDetailId) {
		$this->bidDetailId = $bidDetailId;
	}

	public function getBidDetailId() {
		return ($this->bidDetailId);
	}

	public function setUserDetailArr(UserDetail $userDetailArr) {
		$this->userDetailArr = $userDetailArr;
	}

	public function getUserDetialArr() {
		return ($this->userDetailArr);
	}

	public function setBidStartDateTime($bidStartDateTime) {
		$this->bidStarDateTime = $bidStartDateTime;
	}

	public function getBidStartDateTime() {
		return ($this->bidStarDateTime);
	}

	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	public function getIsActive() {
		return ($this->isActive);
	}

	public function setBidAmount($bidAmount) {
		$this->bidAmount = $bidAmount;
	}

	public function getbidAmount() {
		return ($this->bidAmount);
	}

	public function setBidUpdateDateTime($bidUpdateDateTime) {
		$this->bidUpdateDateTime = $bidUpdateDateTime;
	}

	public function getBidUpdateDateTime() {
		return ($this->bidUpdateDateTime);
	}

	public function setIsClosed($isClosed) {
		$this->isClosed = $isClosed;
	}

	public function getIsClosed() {
		return ($this->isClosed);
	}

	public function setBidText($bidText) {
		$this->bidText = $bidText;
	}

	public function getBidText() {
		return ($this->bidText);
	}

	public function setPostId(PostDetailsDTO $postDetailArr) {
		$this->postId = $postDetailArr;
	}

	public function getPostId() {
		return ($this->postId);
	}

	public function setIntrested($intrested) {
		$this->intrested = $intrested;
	}

	public function getIntrested() {
		return ($this->intrested);
	}
	
	public function setImageArray($imgArray) {
		$this->imageArray = $imgArray;
	}
	
	public function getImageArray() {
		return ($this->imageArray);
	}
	
	public function getImageUrl() {
		return "../controller/basecontroller.php?hdnAction=viewBidImages&bidId=".$this->bidDetailId;
	}
	
	public function getImageCount() {
		$imgArr = $this->imageArray;
		$cnt = 0;
		if (null != $imgArr && isset($imgArr) && is_array($imgArr)) {
			$cnt = count($imgArr);
		}
		return $cnt;
	}
	
	public function __toString() {
		$stringValue = "[BidDeatilDTO= ";
		$stringValue = $stringValue."[Bid Detail=".$this->bidDetailId."]";
		if (null != $this->bidAmount) {
			$stringValue = $stringValue."[BidAmount=".$this->bidAmount."]";
		}
		if (null != $this->postId) {
			$stringValue = $stringValue."[Post Id=".$this->postId."]";
		}
		if (null != $this->intrested) {
			$stringValue = $stringValue."[Intrested =".$this->intrested."]";
		}
		if (null != $this->userDetailArr) {
			$stringValue = $stringValue."[User Detail=".$this->userDetailArr."]";
		}
		return ($stringValue);
	}
}
?>