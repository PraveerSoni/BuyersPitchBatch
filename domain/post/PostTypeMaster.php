<?php
/**
 * Enter description here ...
 * @author PREM
 *
 */
class PostTypeMasterDTO {
	
	private $postTypeMasterId;
	
	private $postTypeName;
	
	private $postTypeDesc;
	
	private $isActive;
	
	public function __construct() {
		
	}
	
	public function setPostTypeMasterId($postTypeMasterId) {
		$this->postTypeMasterId=$postTypeMasterId;
	}
	
	public function setPostTypeName($postTypeName) {
		$this->postTypeName=$postTypeName;
	}
	
	public function setPostTypeDesc($postTypeDesc) {
		$this->postTypeDesc=$postTypeDesc;
	}
	
	public function setIsActive($is_active) {
		$this->isActive=$is_active;
	}
	
	public function getPostTypeMasterId() {
		return ($this->postTypeMasterId);
	}
	
	public function getPostTypeName() {
		return ($this->postTypeName);
	}
	
	public function getPostTypeDesc() {
		return ($this->postTypeDesc);
	}
	
	public function getIsActive() {
		return ($this->isActive);
	}
	
	public function __toString() {
		$stringVal = "";
		if(null != $this->postTypeMasterId) {
			$stringVal = $stringVal."[postTypeMasterId=".$this->postTypeMasterId."]";
		}
		if(null != $this->postTypeName) {
			$stringVal=$stringVal."[postTypeName=".$this->postTypeName."]";
		}
		if(null != $this->postTypeDesc) {
			$stringVal=$stringVal."[postTypeDesc=".$this->postTypeDesc."]";
		}
		if(null != $this->isActive) {
			$stringVal=$stringVal."[isActive=".$this->isActive."]";
		}
		return ($stringVal);
	}
}
?>