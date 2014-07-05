<?php
/**
 * Country DTO to store Country Details.
 * @author PREM
 *
 */
class CountryDTO {
	private $countryId;
	private $countryShortName;
	private $countryLongName;

	/**
	 * Default Constructor
	 */
	public function __construct() {

	}

	/**
	 * Method to set Country Id.
	 * @param unknown_type $countryId
	 */
	public function setCountryId($countryId) {
		$this->countryId=$countryId;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $countryLongName
	 */
	public function setCountryShortName($countryShortName) {
		$this->countryLongName=$countryShortName;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $countryLongName
	 */
	public function setCountryLongName($countryLongName) {
		$this->countryLongName=$countryLongName;
	}

	/**
	 * Enter description here ...
	 * @return unknown_type
	 */
	public function getCountryId() {
		return ($this->countryId);
	}


	/**
	 * Enter description here ...
	 */
	public function getCountryShortName() {
		return ($this->countryShortName);
	}

	/**
	 * Enter description here ...
	 * @return unknown_type
	 */
	public function getCountryLongName() {
		return ($this->countryLongName);
	}
	
	public function __toString() {
		$stringPrint = "";
		if(null != $this->countryId) {
			$stringPrint = $stringPrint." [countryId= ".$this->countryId."]";
		}
		if(null != $this->countryShortName) {
			$stringPrint = $stringPrint." [countryShortName= ".$this->countryShortName."]";
		}
		if(null != $this->countryLongName) {
			$stringPrint = $stringPrint." [countryLongName= ".$this->countryLongName."]";
		}
		return ($stringPrint);
	}
}
?>