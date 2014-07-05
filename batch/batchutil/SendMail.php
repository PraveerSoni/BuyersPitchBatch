<?php
require_once (ROOT_PATH."batch/batchutil/GeneralBatchInclude.php");
require_once (ROOT_PATH."phpmailer/class.phpmailer.php");
class SendMail {

	private $emailTo;

	private $emailFrom;

	private $subject;

	private $body;

	private $smtpServer;

	private $smtpPort;

	private $logger;

	private $smtpUser;

	private $smtpPassword;

	private $isHtmlMsgType;

	/**
	 * Enter description here ...
	 * @param unknown_type $email_to
	 * @param unknown_type $subject
	 * @param unknown_type $body
	 */
	public function __construct($email_to, $subject, $body, $msgType) {
		$this->logger = BatchUtil::getBatchLogger('SendEmail.php');
		$this->emailTo = $email_to;
		$this->subject = $subject;
		$this->body = $body;
		$this->isHtmlMsgType = $msgType;
		$this->emailFrom = BatchUtil::getValueFromKey('HTTP_EMAIL_FROM');
		$this->smtpServer = BatchUtil::getValueFromKey('HTTP_SMTP_ADDR');
		$this->smtpPort = BatchUtil::getValueFromKey('HTTP_SMTP_PORT');
		$this->smtpUser = BatchUtil::getValueFromKey('HTTP_SMTP_USER');
		$this->smtpPassword = BatchUtil::getValueFromKey('HTTP_SMTP_PASSWORD');
	}

	/**
	 * Enter description here ...
	 */
	public function mailSend() {
		$mailSend = false;
		try {
			$this->logger->debug("Before Mail Send Api Call");
			ini_set("SMTP", 'mail.buyerspitch.com');
			ini_set("sendmail_from", $this->emailFrom);
			ini_set("smtp_port", '587');
			ini_set("auth_username", 'buyerkto');
			ini_set("auth_password", $this->smtpPassword);
			mail($this->emailTo, $this->subject, $this->body, $this->emailFrom);
			$mailSend = true;
			$this->logger->debug("After Mail Send Api Call");
		} catch (Exception $e) {
			$this->logger->debug("Error While Sending Mail:: ".$e->getMessage());
		}
		return $mailSend;
	}

	public function sendNonSSLMailUsingPhpMailer() {
		$mailSuccess = false;
		try{
			$defaultTimeZone = BatchUtil::getValueFromKey('HTTP_DEFAULT_TIMEZONE');
			date_default_timezone_set($defaultTimeZone);
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Host = $this->smtpServer;
			$mail->SMTPDebug = 1;
			$mail->SMTPAuth = true;
			$mail->Host = $this->smtpServer;
			$mail->Port = $this->smtpPort;
			$mail->Username = $this->smtpUser;
			$mail->Password = $this->smtpPassword;
			$mail->SetFrom($this->emailFrom);
			$mail->Subject = $this->subject;
			if ($this->isHtmlMsgType) {
				$mail->IsHTML(true);
			}
			$mail->Body = $this->body;
			$mail->AddAddress($this->emailTo,"to");

			if ($mail->Send()) {
				$mailSuccess = true;
				$this->logger->debug("Send Mail successfully to:: ".$this->emailTo);
			} else {
				$this->logger->debug("Fail to send message to:: "+$this->emailTo);
			}
		}catch (Exception $e) {
			$this->logger->error("Error is:: ".$e->getMessage(),$e);
		}
		return $mailSuccess;
	}
}
?>