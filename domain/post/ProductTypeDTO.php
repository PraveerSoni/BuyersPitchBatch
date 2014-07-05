<?php
class ProductTypeDTO {
	
	private $productTypeId;
	
	private $productTypeName;
	
	private $productTypeDesc;
	
	private $isActive;
	
	public function __construct() {
		
	}
	
	public function setProductTypeId($prodTypeId) {
		$this->productTypeId = $prodTypeId;
	}
	
	public function setProductTypeName($prodTypeName) {
		$this->productTypeName = $prodTypeName;
	}
	
	public function setProductTypeDesc($prodTypeDesc) {
		$this->productTypeDesc = $prodTypeDesc;
	}
	
	public function setIsActive($isAct) {
		$this->isActive = $isAct;
	}
	
	public function getProductTypeId() {
		return ($this->productTypeId);
	}
	
	public function getProductTypeName() {
		return ($this->productTypeName);
	}
	
	public function getProductTypeDesc() {
		return ($this->productTypeDesc);
	}
	
	public function getIsActive() {
		return ($this->isActive);
	}
}
?>