<?php
class Product {

	private $productId;

	public function __construct($prodId) {
		$this->productId = $prodId;
	}

	public function getProductId() {
		return $this->productId;
	}

	public function __toString() {
		$string = "Product { ";
		$string = $string."productId = ".$this->productId;
		$string = $string." }";
		return $string;
	}
}
?>