<?php
class ImageDetail {

	private $imageId;

	private $imagePath;

	private $imageName;

	public function __construct() {

	}

	public function setImageId($imageId) {
		$this->imageId = $imageId;
	}

	public function setImagePath($imageData) {
		$this->imagePath = $imageData;
	}

	public function setImageName($imageName) {
		$this->imageName = $imageName;
	}

	public function getImageId() {
		return ($this->imageId);
	}

	public function getImagePath() {
		return ($this->imagePath);
	}

	public function getImageName() {
		return ($this->imageName);
	}

	public function __toString() {
		$strValue = "[ImageDetail ";
		$strValue = $strValue."ImageId=".$this->imageId;
		$strValue = $strValue." imageName=".$this->imageName;
		$strValue = $strValue." imagePath=".$this->imagePath;
		$strValue = $strValue."]";
		return ($strValue);
	}
}
?>