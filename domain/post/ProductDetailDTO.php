<?php
require_once (ROOT_PATH."domain/post/ProductTypeDTO.php");
class ProductDetailDTO {
	
	private $productTypeDTO;
	
	private $productDetailId;
	
	private $productDetailName;
	
	private $productDetailDesc;
	
	private $isActive;
	
	public function __construct() {
		
	}
	
	public function setProductTypeDTO(ProductTypeDTO $prodTypeDTO) {
		$this->productTypeDTO = $prodTypeDTO;
	}
	
	public function setProductDetailId($prodDetailId) {
		$this->productDetailId = $prodDetailId;
	}
	
	public function setProductDetailName($prodDetailName) {
		$this->productDetailName = $prodDetailName;
	}
	
	public function setProductDetailDesc($prodDetailDesc) {
		$this->productDetailDesc = $prodDetailDesc;
	}
	
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}
	
	public function getProductTypeDTO() {
		return ($this->productTypeDTO);
	}
	
	public function getProductDetailId() {
		return ($this->productDetailId);
	}
	
	public function getProductDetailName() {
		return ($this->productDetailName);
	}
	
	public function getProductDetailDesc() {
		return ($this->productDetailDesc);
	}
	
	public function getIsActive() {
		return ($this->isActive);
	}
	
}
?>