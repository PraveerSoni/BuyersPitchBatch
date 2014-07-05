<?php
require_once ("../util/GenericInclude.php");
/**
 * Abstract DB Class for Database Generic Handling.
 * @author PREM
 *
 */
final class AbstractDB {
	

	/**
	 * Enter description here ...
	 */
	private function __construct() {
			
	}

	/**
	 * Method to get MySql Database Connection.
	 * @return resource
	 */
	public static function getConnection() {
		$logger = GeneralUtil::getLogger('AbstractDB.php');
		$host = GeneralUtil::getEnvValue(Constants::HTTP_DB_HOST);
		$logger->debug("Host Name:: ".$host);
		$user=GeneralUtil::getEnvValue(Constants::HTTP_DB_USER);
		$logger->debug("DB User Name:: ".$user);
		$password=GeneralUtil::getEnvValue(Constants::HTTP_DB_PASSWORD);
		$logger->debug("DB Passwor:: ".$password);
		$dbName=GeneralUtil::getEnvValue(Constants::HTTP_DB_NAME);
		$logger->debug("DB Name:: ".$dbName);
		$con = new mysqli($host,$user,$password,$dbName);
		if(empty($con)) {
			die('Could not connect: ' . mysqli_errno());
		}
		$con->autocommit(FALSE);
		return ($con);
	}

	/**
	 * Method to clean database resources.
	 * @param unknown_type $conn
	 */
	public static function cleanUp($conn,$stmt) {
		$stmt->close();
		$conn->close();
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $stmt
	 */
	public static function cleanUpStatement($stmt) {
		$stmt->close();
	}
}
?>