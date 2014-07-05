<?php
require_once (ROOT_PATH."domain/taxonomy/Category.php");
class TaxonomyProductCategory {

	private $categoryId;

	private $productId;

	public function __construct($catId, $prodId) {
		$this->categoryId = $catId;
		$this->productId = $prodId;
	}

	public function getCategoryId() {
		return $this->categoryId;
	}

	public function getProductId() {
		return $this->productId;
	}

	private function checkAndFormCategory($categorys) {
		$iLen = count($categorys);
		$category = null;
		for($i = 0; $i < $iLen; $i++) {
			if($categorys[$i]->getCategoryId() == $this->categoryId) {
				$category = $categorys[$i];
				$category->setProductIds($this->productId);
				array_splice($categorys, $i, 1);
				break;
			}
		}
		if(null == $category) {
			$category = new Category($this->categoryId);
			$category->setProductIds($this->productId);
		}
		array_push($categorys, $category);
		return $categorys;
	}
	
	public function formCategoriesFromProductCategory($categorys) {
		if(null == $categorys && !isset($categorys) && !is_array($categorys)) {
			$categorys = array();
		}
		$categorys = $this->checkAndFormCategory($categorys);
		return $categorys;
	}
}
?>