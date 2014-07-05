<?php
require_once (ROOT_PATH."batch/batchdao/BatchDetailDAO.php");
require_once (ROOT_PATH."persistence/AbstarctPDODB.php");
class BatchDetailDAOImpl extends AbstractPDODB implements BatchDetailDAO {

	private $logger;

	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('BatchDAOImpl.php');
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDAO::createBatchDetail()
	*/
	public function createBatchDetail(BatchDetail $batchDetail) {
		$dbh = null;
		$status=1;
		try {
			$sql = BatchSQLQueryConstant::SQL_INS_BATCH_DET;
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$batchType = $batchDetail->getBatchType()->getBatchTypeId();
			$stmt->bindParam(1, $batchType, PDO::PARAM_INT);
			$stmt->bindParam(2, $status,PDO::PARAM_INT);
			$dbh->beginTransaction();
			$stmt->execute();
			$id = $dbh->lastInsertId('batch_details_id');
			$this->logger->debug("Inserted batch_details_id:: ".$id);
			$batchDetail->setBatchDetailId($id);
			$dbh->commit();
			$dbh = null;
		} catch (PDOException $e) {
			$dbh->rollBack();
			$this->logger->error("Error While Create Batch:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		return ($batchDetail);
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDAO::updateBatchDetail()
	*/
	public function updateBatchDetail(BatchDetail $batchDetail) {
		$dbh = null;
		try {
			$sql = BatchSQLQueryConstant::SQL_UPD_BATCH_DET;
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$status = $batchDetail->getBatchStatusId();
			//$updatedTime = date();
			$id = $batchDetail->getBatchDetailId();
			$stmt->bindParam(1, $status, PDO::PARAM_INT);
			//$stmt->bindParam(2, $updatedTime);
			$stmt->bindParam(2, $id, PDO::PARAM_INT);
			$dbh->beginTransaction();
			$stmt->execute();
			$dbh->commit();
		} catch (PDOException $e) {
			$dbh->rollBack();
			$this->logger->error("Error While Create Batch:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDAO::updateLastId()
	*/
	public function updateLastId($id, $batchType) {
		$dbh = null;
		try {
			$sql = BatchSQLQueryConstant::SQL_UPD_BATCH_CTRL;
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $id);
			$stmt->bindParam(2, $batchType);
			$dbh->beginTransaction();
			$stmt->execute();
			$dbh->commit();
		} catch (PDOException $e) {
			$dbh->rollBack();
			$this->logger->error("Error While Create Batch:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDAO::getBatchTypeDetails()
	*/
	public function getBatchTypeDetails($batchTypeId) {
		$dbh = null;
		$batchType = null;
		try {
			$sql = BatchSQLQueryConstant::SQL_SEL_BATCH_TYPE_run;
			$dbh = $this->formConnection();
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $batchTypeId, PDO::PARAM_INT);
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$batchType = new BatchType();
				$batchType->setBatchTypeId($batchTypeId);
				$batchType->setBatchTypeName($row->batch_type_name);
				//$batchType->setBatchDesc($row->batch_type_desc);
				$batchType->setBatchSize($row->batch_size);
				$batchType->setLastReadId($row->last_read_id);
			}
			$dbh = null;
			if (null == $batchType) {
				throw new NoDataFoundException("No Batch Type Defined for batch type id ".$batchTypeId, 4, null);
			}
		} catch (PDOException $e) {
			$this->logger->error("Error While Get Batch Type:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		return ($batchType);
	}

	/* (non-PHPdoc)
	 * @see BatchDetailDAO::checkBatchRunningStatus()
	*/
	public function checkBatchRunningStatus($batchTypeId) {
		$dbh = null;
		$status = null;
		$activeFlag = 'T';
		try {
			$sql = BatchSQLQueryConstant::SQL_SEL_BATCH_RUN_STATUS;
			$dbh = $this->formConnection();
			$this->logger->debug("Running Sql:: ".$sql);
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $batchTypeId, PDO::PARAM_INT);
			$stmt->bindParam(2, $activeFlag, PDO::PARAM_STR);
			$stmt->execute();
			$this->logger->debug("Execute");
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
				$status = $row->status;
				$this->logger->debug("Status:::: ".$status);
			}
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While Get Batch Type:: ".$e->getMessage());
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
		if (null == $status) {
			throw new NoDataFoundException("No Batch Type Defined for batch type id ".$batchTypeId, 4, null);
		}
		return ($status);
	}

	public function uppdateBatchType(BatchType $batchType) {
		$dbh = null;
		$status = $batchType->getBatchRunStatus();
		$batchTypeId = $batchType->getBatchTypeId();
		try {
			$sql = BatchSQLQueryConstant::SQL_UPDATE_RUN_STATUS;
			$dbh = $this->formConnection();
			$this->logger->debug("Sql:: ".$sql);
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(1, $status, PDO::PARAM_INT);
			$stmt->bindParam(2, $batchTypeId, PDO::PARAM_STR);
			$dbh->beginTransaction();
			$stmt->execute();
			$dbh->commit();
			$dbh = null;
		} catch (PDOException $e) {
			$this->logger->error("Error While Get Batch Type:: ".$e->getMessage());
			$dbh->rollBack();
			$dbh = null;
			throw new InternalException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
?>