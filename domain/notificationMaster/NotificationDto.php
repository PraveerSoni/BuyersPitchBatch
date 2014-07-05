<?php
require_once (ROOT_PATH.'domain/userdetail/UserDetail.php');
require_once (ROOT_PATH."domain/taxonomy/Category.php");
class NotificationDto {

	private $id;

	private $email;

	private $mobileNumber;

	private $keywords;

	private $userDetail;

	private $category;

	private $catIds;

	private $prodIds;
	
	private $logger;

	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('NotificationDto.php');
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function setMobileNumber($mobile) {
		$this->mobileNumber = $mobile;
	}

	public function getMobileNumber() {
		return $this->mobileNumber;
	}

	public function setUserDetail($userDetail) {
		$this->userDetail = $userDetail;
	}

	public function getUserDetail() {
		return $this->userDetail;
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

	public function setCatIds($catId) {
		$this->catIds = $catId;
	}

	public function setProdIds($prodId) {
		$this->prodIds = $prodId;
	}

	public function getProdIds() {
		return $this->prodIds;
	}

	public function formCategoryProduct() {
		if(null != $this->categoryId) {
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
						//$prod = $product[$j];
						$strPodId = $product[$j]->getProductId();
						$this->logger->debug("StrProdId:: ".$strPodId);
						$this->prodIds=$this->prodIds.$strPodId;
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
		$stringValue = "NotificationDto { id=".$this->id;
		$stringValue = $stringValue.", keywords=".$this->keywords;
		$stringValue = $stringValue."}";
		return $stringValue;
	}
}
?>