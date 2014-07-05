<?php
require_once (ROOT_PATH."domain/post/PostTypeMaster.php");
require_once (ROOT_PATH."domain/userdetail/UserDetail.php");
require_once (ROOT_PATH."domain/ImageDetail.php");
require_once (ROOT_PATH."domain/post/ProductDetailDTO.php");
require_once (ROOT_PATH."domain/taxonomy/Category.php");
class PostDetailsDTO {

	private $postDetailsId;

	private $notifyPostId;

	private $postText;

	private $postTitle;

	private $postCreatedDateTime;

	private $postUpdateDateTime;

	private $isPostClosed;

	private $isActive;

	private $postType;

	private $userDetail;

	private $isBid;

	private $imageArray;

	private $productDetailDTO;

	private $duration;

	private $endDateTime;

	private $durationInDays;

	private $durationInHours;

	private $postEndDateTime;

	private $categoryId;

	private $catIds;

	private $prodIds;

	private $taxonomyFlag;

	public function __construct() {

	}

	public function setPostDetailsId($postDetailsId) {
		$this->postDetailsId=$postDetailsId;
	}

	public function setPostText($postText) {
		$this->postText=$postText;
	}

	public function setPostCreatedDateTime($postCreatedDateTime) {
		$this->postCreatedDateTime=$postCreatedDateTime;
	}

	public function setPostUpdateDateTime($postUpdateDateTime) {
		$this->postUpdateDateTime=$postUpdateDateTime;
	}

	public function setIsPostClosed($isPostClosed) {
		$this->isPostClosed=$isPostClosed;
	}

	public function setIsActive($isActive) {
		$this->isActive=$isActive;
	}

	public function setPostType(PostTypeMasterDTO $postType) {
		$this->postType=$postType;
	}

	public function setUserDetail(UserDetail $userDetail) {
		$this->userDetail=$userDetail;
	}

	public function getPostDetailsId() {
		return ($this->postDetailsId);
	}

	public function getPostText() {
		return ($this->postText);
	}

	public function getPostCreatedDateTime() {
		return ($this->postCreatedDateTime);
	}

	public function getPostUpdateDateTime() {
		return ($this->postUpdateDateTime);
	}

	public function getIsPostClosed() {
		return ($this->isPostClosed);
	}

	public function getIsActive() {
		return ($this->isActive);
	}

	public function getPostType() {
		return ($this->postType);
	}

	public function getUserDetail() {
		return ($this->userDetail);
	}

	public function setIsBid($isBid) {
		$this->isBid=$isBid;
	}

	public function getIsBid() {
		return ($this->isBid);
	}

	public function setImageArray($imgArr) {
		$this->imageArray = $imgArr;
	}

	public function getImageArray() {
		return ($this->imageArray);
	}

	public function setProductDetailDTO(ProductDetailDTO $productDetailDTO) {
		$this->productDetailDTO = $productDetailDTO;
	}

	public function getProductDetailDTO() {
		return $this->productDetailDTO;
	}

	public function setDuration($duration) {
		$this->duration = $duration;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function setEndDateTime($endDateTime) {
		$this->endDateTime = $endDateTime;
	}

	public function getEndDateTime() {
		return $this->endDateTime;
	}

	public function getDurationInHours() {
		$hour = null;
		$pos = strpos($this->duration, 'h');
		if ($pos) {
			$hour = substr($this->duration, 0,$pos);
		}
		return $hour;
	}

	public function getDurationInDays() {
		$day = null;
		$pos = strpos($this->duration, 'd');
		if ($pos) {
			$day = substr($this->duration, 0,$pos);
		}
		return $day;
	}

	public function setPostEndDateTime($postEndDateTime) {
		$this->postEndDateTime = $postEndDateTime;
	}

	public function getPostEndDateTime() {
		return $this->postEndDateTime;
	}

	public function setNotifyPostId($notifyPostId) {
		$this->notifyPostId = $notifyPostId;
	}

	public function getNotifyPostId() {
		return ($this->notifyPostId);
	}

	public function setPostTitle($postTitle) {
		$this->postTitle = $postTitle;
	}

	public function getPostTitle() {
		return $this->postTitle;
	}

	public function setCategoryId($catId) {
		$this->categoryId = $catId;
	}

	public function getCategoryId() {
		if(null == $this->categoryId && !isset($this->categoryId) && !is_array($this->categoryId)) {
			$this->categoryId = array();
		}
		return $this->categoryId;
	}

	public function getCatIds() {
		return $this->catIds;
	}

	public function getProdIds() {
		return $this->prodIds;
	}

	public function setCatIds($catId) {
		$this->catIds = $catId;
	}

	public function setProdIds($prodId) {
		$this->prodIds = $prodId;
	}

	public function formCategoryProduct() {
		if(null != $this->categoryId) {
			$this->setTaxonomyFlag('notnull');
			$catCnt = count($this->categoryId);
			$this->catIds='';
			$this->prodIds='';
			for($i = 0; $i <$catCnt; $i++) {
				$category = $this->categoryId[$i];
				$this->catIds=$this->catIds.$category->getCategoryId();
				$product = $category->getProductIds();
				$prodCnt = count($product);
				if($prodCnt == 0) {
					$this->prodIds = $this->prodIds."-1";
				} else {
					for($j = 0; $j < $prodCnt; $j++) {
						$prod = $product[$j];
						$this->prodIds=$this->prodIds.$prod->getProductId();
						if($j < $prodCnt - 1) {
							$this->prodIds=$this->prodIds.",";
						}
					}
				}
				if($i < $catCnt-1) {
					$this->catIds=$this->catIds."|";
					$this->prodIds=$this->prodIds."|";
				}
			}
		}
	}

	public function setTaxonomyFlag($str) {
		if($str != 'null') {
			if(null != $this->catIds) {
				$this->taxonomyFlag=1;
			} else{
				$this->taxonomyFlag=0;
			}
		}
	}

	public function getTaxonomyFlag() {
		return $this->taxonomyFlag;
	}

	public function breakCategoryProduct() {
		if(null != $this->catIds) {
			$strCatArray = BatchUtil::splitString("|", $this->catIds);
			$strProdArray = BatchUtil::splitString("|", $this->prodIds);
			$iLen = count($strCatArray);
			$this->categoryId = array();
			for($i = 0; $i < $iLen; $i++) {
				$category = new Category($strCatArray[$i]);
				$strSubProdArray = BatchUtil::splitString(",", $strProdArray[$i]);
				$cnt = count($strSubProdArray);
				for($j = 0; $j < $cnt; $j++) {
					$prodId = $strSubProdArray[$j];
					if($prodId != -1) {
						$category->setProductIds($prodId);
					}
				}
				array_push($this->categoryId, $category);
			}
		}
	}

	public function __toString() {
		$stringValue="[Post Text=".$this->postText."]";
		if(null != $this->taxonomyFlag) {
			$stringValue=$stringValue."[taxonomyFlag=".$this->taxonomyFlag."]";
		}
		if(null != $this->categoryId) {
			$stringValue=$stringValue."[categoryId=".$this->categoryId."]";
		}
		return ($stringValue);
	}
}
?>