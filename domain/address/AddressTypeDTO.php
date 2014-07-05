<?php
class AddressTypeDTO {

	private $addressTypeId;

	private $addressTypeName;

	private $addressTypeDesc;

	public function __construct() {

	}

	public function setAddressTypeId($addrTypeId) {
		$this->addressTypeId = $addrTypeId;
	}

	public function setAddressTypeName($addrTypeName) {
		$this->addressTypeName = $addrTypeName;
	}

	public function setAddressTypeDesc($addrTypeDesc) {
		$this->addressTypeDesc = $addrTypeDesc;
	}

	public function getAddressTypeId() {
		return $this->addressTypeId;
	}

	public function getAddressTypeName() {
		return $this->addressTypeName;
	}

	public function getAddressTypeDesc() {
		return $this->addressTypeDesc;
	}
	
	public function __toString() {
		$value = "[AddressType ";
		$value = $value."AddressTypeId=".$this->addressTypeId;
		$value = $value." ]";
		return $value;
	}
}
?>