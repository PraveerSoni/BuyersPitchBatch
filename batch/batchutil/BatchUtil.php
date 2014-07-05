<?php
require_once (ROOT_PATH.'batch/batchutil/GeneralBatchInclude.php');
final class BatchUtil {

	private static $stopWordArray = array('require','software developer','black','i','to','has','want','buy','sell','am','a','rs','have','had','will','would','shall','should','need', 'experience','with','for','hour','hr','required');
	
	private static $gam = array('.');
	
	private function __construct() {

	}

	public static function getBatchLogger($className) {
		$log4phpFile = BatchUtil::getDefinedValue('LOG_FILE_NAME');
		Logger::configure($log4phpFile);
		$logger = Logger::getLogger($className);
		return ($logger);
	}

	public static function getDefinedValue($definedParam) {
		$value = constant($definedParam);
		//echo "Value:: ".$value."\n";
		return $value;
	}

	public static function getValueFromKey($key) {
		$value = ConfigXMLReader::getInstance(ROOT_PATH.'batch/batchutil/configfile.xml')->getVlaueFromKey($key);
		return $value;
	}

	public static function sendMail(EmailData $emailData) {
		$mail = new SendMail($emailData->getToEmailId(), $emailData->getSubject(), $emailData->getBody(), $emailData->getMsgType());
		$status = $mail->sendNonSSLMailUsingPhpMailer();
		return ($status);
	}

	public static function compareTextArrays($textArray1, $textArray2) {
		$cnt1 = count($textArray1);
		$cnt2 = count($textArray2);
		$bFlag = 'N';
		for($i = 0; $i < $cnt1; $i++) {
			$text1 = $textArray1[$i];
			for($j = 0; $j < $cnt2; $j++) {
				$text2 = $textArray2[$j];
				if(strcasecmp($text1, $text2)) {
					$bFlag = 'Y';
					break;
				}
			}
			if(strcasecmp($bFlag, 'Y')) {
				break;
			}
		}
	}

	public static function sendEmailFromTemplate(EmailData $emailData, $bodyContext, $subjectContext) {
		$logger = BatchUtil::getBatchLogger('BatchUtil.php');
		$mailSubject = $emailData->getSubject();
		$mailBody = $emailData->getBody();
		if(null != $subjectContext && isset($subjectContext)) {
			$cnt = count($subjectContext);
			for($i = 0; $i < $cnt; $i++) {
				$search = '{'.$i.'}';
				$mailSubject = str_replace($search, $subjectContext[$i], $mailSubject);
			}
		}
		if(null != $bodyContext && isset($bodyContext)) {
			$cnt = count($bodyContext);
			for($i = 0; $i < $cnt; $i++) {
				$search = '{'.$i.'}';
				$mailBody = str_replace($search, $bodyContext[$i], $mailBody);
			}
		}
		$mailData = new EmailData($emailData->getToEmailId(), $mailSubject, $mailBody, true);
		$logger->debug("Mail Body:: ".$mailBody);
		BatchUtil::sendMail($mailData);
	}

	public static function removeStopWords($string) {
		$logger = BatchUtil::getBatchLogger('BatchUtil.php');
		$stopWordArr = BatchUtil::$stopWordArray;
		$cnt = count($stopWordArr);
		for($i = 0; $i < $cnt; $i++) {
			$word = $stopWordArr[$i];
			$string = preg_replace('/'.$word.'\b/i','',$string);
		}
		$cnt = count(BatchUtil::$gam);
		for($i = 0; $i < $cnt; $i++) {
			$string = str_replace(BatchUtil::$gam[$i], '', $string);
		}
		$string=ltrim($string);
		$string=rtrim($string);
		$logger->debug("Changed String:: ".$string);
		return $string;
	}

	public static function splitString($delimiter, $string) {
		$returnArray = array();
		if(null != $string && strlen(ltrim(rtrim($string)))){
			if(null == $delimiter) {
				$delimiter = ',';
			}
			$string = ltrim(rtrim($string));
			$returnArray = explode($delimiter, $string);
		}
		return $returnArray;
	}
}
?>