<?php
abstract class AbstractPDODB {

	protected function formConnection() {
		$user='root';
		$password='';
		$dbh=null;
		try {
			$dbh=new PDO('mysql:host=localhost;dbname=buyerkto_buyer', $user, $password);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $dbh;
		} catch (PDOException $e) {
			throw $e;
		}
	}

	protected function formSqlWithInClause($array, $columnName) {
		$sql = null;
		$iLen = count($array);
		if($iLen > 0) {
			$sql = $columnName." in (";
			for($i = 0; $i < $iLen; $i++) {
				$sql = $sql."?";
				if($i < $iLen -1) {
					$sql = $sql.",";
				}
			}
			$sql = $sql.")";
		}
		return $sql;
	}

	protected function setBindVariables($start,$array,$stmt, $type) {
		$returnArray = null;
		$iLen = count($array);
		if($iLen > 0) {
			$returnArray = array();
			for($i = 0; $i < $iLen; $i++) {
				$stmt = $this->setValue($stmt, $type, $start, $array[$i]);
				$start = $start + 1;
			}
			array_push($returnArray, $stmt);
			array_push($returnArray, $start);
		}
		return $returnArray;
	}
	
	protected function setBindVariablesFromObject($start,$array,$stmt, $type, $objectName, $methodName) {
		$returnArray = null;
		$iLen = count($array);
		if($iLen > 0) {
			$returnArray = array();
			$iLen = count($array);
			for($i = 0; $i < $iLen; $i++) {
				$stmt = $this->setValue($stmt, $type, $start, $array[$i]->getProductId());
				$start = $start + 1;
			}
			array_push($returnArray, $stmt);
			array_push($returnArray, $start);
		}
		return $returnArray;
	}

	private function setValue($stmt, $type, $place, $value) {
		$type = strtolower($type);
		if($type == 'string') {
			$stmt->bindParam($place, $value, PDO::PARAM_STR);
		} elseif ($type == 'int') {
			$stmt->bindParam($place, $value, PDO::PARAM_INT);
		}
		return $stmt;
	}
}
?>