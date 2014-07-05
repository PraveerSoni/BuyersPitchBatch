<?php
require_once (ROOT_PATH."domain/usertype/UserTypeMasterDTO.php");
require_once (ROOT_PATH."domain/address/AddressDetail.php");
require_once (ROOT_PATH."domain/notificationMaster/NotificationTypeMaster.php");
class UserDetail {

	private $userPkId;

	private $userId;

	private $fName;

	private $lName;

	private $email;

	private $contactNo;

	private $userType;

	private $address;

	private $userLogPkId;

	private $userIpAddress;

	private $isActive;

	private $isNotifyActive;

	private $notifyMaster;
	
	private $password;
	
	private $activationKey;

	public function __construct() {

	}

	public function setUserPkId($user_pk_id) {
		$this->userPkId=$user_pk_id;
	}

	public function getUserpkId() {
		return ($this->userPkId);
	}

	public function setUserId($user) {
		$this->userId = $user;
	}

	public function setFName($fname) {
		$this->fName=$fname;
	}

	public function setLName($lname) {
		$this->lName=$lname;
	}

	public function setEmail($emaidId) {
		$this->email=$emaidId;
	}

	public function setContactNo($contactNumber) {
		$this->contactNo=$contactNumber;
	}

	public function setUserType(UserTypeMasterDTO $userTypeDTO) {
		$this->userType=$userTypeDTO;
	}

	public function setAddress($addrDetail) {
		$this->address=$addrDetail;
	}

	public function getUserId() {
		return ($this->userId);
	}

	public function getFName() {
		return ($this->fName);
	}

	public function getLName() {
		return ($this->lName);
	}

	public function getEmail() {
		return ($this->email);
	}

	public function getContactNo() {
		return ($this->contactNo);
	}

	public function getUserType() {
		return ($this->userType);
	}

	public function getAddress() {
		return ($this->address);
	}

	public function setUserLogPkId($userLogPkId) {
		$this->userLogPkId=$userLogPkId;
	}

	public function getUserLogPkId() {
		return ($this->userLogPkId);
	}

	public function setUserIpAddress($userIpAddress) {
		$this->userIpAddress=$userIpAddress;
	}

	public function getUserIpAddress() {
		return ($this->userIpAddress);
	}

	public function  setIsActive($isActive) {
		$this->isActive=$isActive;
	}

	public function getIsActive() {
		return ($this->isActive);
	}

	public function getFullName() {
		$fullName = $this->fName." ".$this->lName;
		return ($fullName);
	}

	public function setIsNotifyActive($isNotifyActive) {
		$this->isNotifyActive = $isNotifyActive;
	}

	public function getIsNotifyActive() {
		return ($this->isNotifyActive);
	}

	public function setNotifyMaster($notifyMaster) {
		$this->notifyMaster = $notifyMaster;
	}

	public function getNotifyMaster() {
		return ($this->notifyMaster);
	}
	
	public function setPassword($pass) {
		$this->password = $pass;
	}
	
	public function getPassword() {
		return ($this->password);
	}
	
	public function setActivationKey($actKey) {
		$this->activationKey = $actKey;
	}
	
	public function getActivationKey() {
		return ($this->activationKey);
	}
	
	public function __toString() {
		$stringVal = "[userName]=".$this->userId;
		$stringVal = $stringVal."[userpk id]=".$this->userPkId;
		$stringVal = $stringVal."[Password]=".$this->password;
		if(null != $this->isActive) {
			$stringVal = $stringVal."[isActive]=".$this->isActive;
		}
		if (null != $this->userIpAddress) {
			$stringVal = $stringVal."[userIpAddress]=".$this->userIpAddress;
		}
		if (null != $this->userLogPkId) {
			$stringVal = $stringVal."[userLogPkId]=".$this->userLogPkId;
		}
		if(null != $this->userPkId) {
			$stringVal = $stringVal."[userPkId]=".$this->userPkId;
		}
		if(null != $this->fName) {
			$stringVal = $stringVal."[fName]=".$this->fName;
		}
		if(null != $this->email) {
			$stringVal = $stringVal." [email]=".$this->email;
		}
		if(null != $this->contactNo) {
			$stringVal = $stringVal." [contactNo]=".$this->contactNo;
		}
		if(null != $this->address) {
			$stringVal = $stringVal." [address]=".$this->address;
		}
		if(null != $this->userType) {
			$stringVal = $stringVal." [userType]=".$this->userType;
		}
		return ($stringVal);
	}
}
?>