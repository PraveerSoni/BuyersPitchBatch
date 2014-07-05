<?php
require_once (ROOT_PATH."domain/country/CountryDTO.php");
require_once (ROOT_PATH."domain/address/AddressTypeDTO.php");
class AddressDetail {

	private $addressId;

	private $addressType;

	private $addressLine1;

	private $addressLine2;

	private $city;

	private $state;

	private $country;

	private $isActive;
	
	private $pinCode;

	public function __construct() {

	}
	
	public function setPinCode($pinCode) {
		$this->pinCode = $pinCode;
	}
	
	
	public function getPinCode() {
		return $this->pinCode;
	}
	public function setAddressId($addressId) {
		$this->addressId = $addressId;
	}

	public function setAddressLine1($addrLine1) {
		$this->addressLine1=$addrLine1;
	}

	public function setAddressLine2($addrLine2) {
		$this->addressLine2=$addrLine2;
	}

	public function setCity($city) {
		$this->city=$city;
	}

	public function setState($state) {
		$this->state=$state;
	}

	public function setCountry(CountryDTO $contryDTO) {
		$this->country=$contryDTO;
	}

	public function getAddressId() {
		return  ($this->addressId);
	}

	public function getAddressLine1() {
		return ($this->addressLine1);
	}

	public function getAddressLine2() {
		return ($this->addressLine2);
	}

	public function getCity() {
		return ($this->city);
	}

	public function getState() {
		return ($this->state);
	}

	public function getCountry() {
		return ($this->country);
	}

	public function setIsActive($isActive) {
		$this->isActive=$isActive;
	}

	public function getIsActive() {
		return ($this->isActive);
	}

	public function setAddressType($addrType) {
		$this->addressType = $addrType;
	}

	public function getAddressType() {
		return $this->addressType;
	}

	public function __toString() {
		$stringPrint = "";
		if(null != $this->addressId) {
			$stringPrint = $stringPrint." [addressId= ".$this->addressId."]";
		}
		if(null != $this->addressLine1) {
			$stringPrint = $stringPrint." [addressLine1= ".$this->addressLine1."]";
		}
		if(null != $this->addressLine2) {
			$stringPrint = $stringPrint." [addressLine2= ".$this->addressLine2."]";
		}
		if(null != $this->city){
			$stringPrint = $stringPrint." [city= ".$this->city."]";
		}
		if(null != $this->state) {
			$stringPrint = $stringPrint." [state= ".$this->state."]";
		}
		if(null != $this->isActive) {
			$stringPrint = $stringPrint." [isActive= ".$this->isActive."]";
		}
		if(null != $this->country) {
			$stringPrint = $stringPrint." [country= ".$this->country."]";
		}
		$stringPrint = $stringPrint." [PinCode= ".$this->pinCode."]";
		$stringPrint = $stringPrint." [AddressType= ".$this->addressType."]";
		return ($stringPrint);
	}
}
?>