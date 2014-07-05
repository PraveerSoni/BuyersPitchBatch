<?php
require_once (ROOT_PATH.'domain/taxonomy/Taxanomys.php');
require_once (ROOT_PATH.'batch/batchutil/MarshalUnMarshal.php');
class TaxonomyReader {
	
	private $logger;
	
	public function __construct() {
		$this->logger = BatchUtil::getBatchLogger('TaxonomyReader.php');
	}
	
	public function createTaxonomyObject() {
		$xmlFilePath = BatchUtil::getValueFromKey('TaxonomyXMLFilePath');
		$bindingFilePath = BatchUtil::getValueFromKey('PiBXBindingFile');
		$xml = file_get_contents($xmlFilePath, true);
		$marshalUnMarshal = new MarshalUnMarshal();
		$taxonomys = $marshalUnMarshal->getObjectFromXml($bindingFilePath, $xml);
		$taxonomyArray = $taxonomys->getTaxanomys();
		$cnt = count($taxonomyArray);
		$this->logger->debug("Array Size:: ".$cnt);
		for($i = 0; $i<$cnt; $i++) {
			$this->logger->debug("Taxonomy Obj:: ".$taxonomyArray[$i]);
		}
	}
}
?>