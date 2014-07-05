<?php
/**
 * Enter description here ...
 * @author PREM
 *
 */
class NotificationTypeMaster {

	private $notificationTypeMasterId;

	private $notificationTypeName;

	private $isActive;

	/**
	 * Enter description here ...
	 */
	public function __construct() {

	}

	/**
	 * Enter description here ...
	 * @param unknown_type $notificationTypeMasterId
	 */
	public function setNotificationTypeMasterId($notificationTypeMasterId) {
		$this->notificationTypeMasterId = $notificationTypeMasterId;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $notificationTypeName
	 */
	public function setNotificationTypeName($notificationTypeName) {
		$this->notificationTypeName = $notificationTypeName;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	/**
	 * Enter description here ...
	 * @return unknown_type
	 */
	public function getNotificationTypeMasterId() {
		return ($this->notificationTypeMasterId);
	}

	/**
	 * Enter description here ...
	 * @return unknown_type
	 */
	public function getNotificationTypeName() {
		return ($this->notificationTypeName);
	}

	/**
	 * Enter description here ...
	 * @return unknown_type
	 */
	public function getIsActive() {
		return ($this->isActive);
	}

	/**
	 * Enter description here ...
	 * @return string
	 */
	public function __toString() {
		$strValue = "";
		$strValue = $strValue."[Notification Type Master = [notificationTypeMasterId=".$this->notificationTypeMasterId;
		$strValue = $strValue."]]";
		return ($strValue);
	}
}
?>