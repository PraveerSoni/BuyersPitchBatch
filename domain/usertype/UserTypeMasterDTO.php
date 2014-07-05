<?php
/**
 * User Type Master DTO to store User Type Master Data.
 * @author PREM
 *
 */
class UserTypeMasterDTO {
	private $userTyeMasterId;
	private $userTypeName;
	private $userTypeDesc;
	private $isActive;

	/**
	 * Default Constructor.
	 */
	function __construct() {

	}

	/**
	 * Method to set user master type id.
	 * @param unknown_type $userTypeMasterId
	 */
	public function setUserTypeMasterId($userTypeMasterId) {
		$this->userTyeMasterId=$userTypeMasterId;
	}

	/**
	 * Method to set user master type name.
	 * @param unknown_type $userTypeName
	 */
	public function setUserTypeName($userTypeName) {
		$this->userTypeName = $userTypeName;
	}

	/**
	 * Metod to set user tpe des.
	 * @param unknown_type $userTpeDesc
	 */
	public function setUserTypeDesc($userTpeDesc) {
		$this->userTypeDesc = $userTpeDesc;
	}

	/**
	 * Method to set is active flag.
	 * @param unknown_type $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive=$isActive;
	}

	public function getUserTypeMasterId() {
		return ($this->userTyeMasterId);
	}

	public function getUserTypeName() {
		return ($this->userTypeName);
	}

	public function getUserTypeDesc() {
		return ($this->userTypeDesc);
	}

	public function getIsActive() {
		return ($this->isActive);
	}

	/**
	 * ToString Method
	 * @return Ambigous <string, number>
	 */
	public function __toString() {
		$string = "";
		if(null != $this->userTyeMasterId) {
			$string = $string ."[userTyeMasterId] = ".$this->userTyeMasterId;
		}if(null != $this->userTypeName) {
			$string = $string." [userTypeName] = ".$this->userTypeName;
		}if(null != $this->userTypeDesc) {
			$string = $string." [userTypeDesc] = ".$this->userTypeDesc;
		}if(null != $this->isActive) {
			$string = $string." [isActive] = ".$this->isActive;
		}
		return ($string);
	}
}
?>