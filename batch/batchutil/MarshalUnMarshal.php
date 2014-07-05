<?php
require_once (ROOT_PATH.'PiBX/Runtime/Unmarshaller.php');
require_once (ROOT_PATH.'PiBX/Runtime/Binding.php');
require_once (ROOT_PATH.'exception/InternalException.php');
class MarshalUnMarshal {

	public function getObjectFromXml($bindingXmlPath, $xml) {
		$binding = new PiBX_Runtime_Binding($bindingXmlPath);
		$unmarshaller = new PiBX_Runtime_Unmarshaller($binding);
		try {
			$collectionObject = $unmarshaller->unmarshal($xml);
			return $collectionObject;
		} catch (Exception $e) {
			throw new InternalException("Error While Unmarshing xml:: ".$e->getMessage(), 400, $e);
		}
	}
}
?>