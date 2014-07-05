<?php
class ConfigXMLReader {
	private static $instance;

	private $configArray;

	private function __construct($filePath) {
		$obj = new DOMDocument();
		$obj->load($filePath);
		$configFile = $obj->getElementsByTagName("configfile");
		foreach ($configFile as $value) {
			$fileNameTag = $value->getElementsByTagName("fileName");
			$fileName = trim($fileNameTag->item(0)->nodeValue);
			$fileObj = new DOMDocument();
			$fileObj->load($fileName);
			$config = $fileObj->getElementsByTagName("config");
			$configArray = array();
			foreach ($config as $value) {
				$configNameTag = $value->getElementsByTagName("configname");
				$configName = trim($configNameTag->item(0)->nodeValue);
				$configValueTag = $value->getElementsByTagName("configvalue");
				$configValue = trim($configValueTag->item(0)->nodeValue);
				$this->configArray[$configName] = $configValue;
			}
		}
	}

	public static function getInstance($filePath) {
		if (!is_object(self::$instance)) {
			self::$instance = new ConfigXMLReader($filePath);
		}
		return self::$instance;
	}

	public function getVlaueFromKey($keyRet) {
		$value = null;
		if (null != $this->configArray && isset($this->configArray)) {
			$value = $this->configArray[$keyRet];
		}
		return $value;
	}
}
?>