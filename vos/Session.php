<?php
/**
 * Session.php
 * Contain session information
 * Class common to all applications
 */

class Session  {

	var $userId;
	var $ipAddress;
	var $userAgent;
	var $remember;
	var $sessionId;
	var $languageCode;
	
	/**
	 * Set data from data array
	 * @param $data array
	 */
	function setDataFromArray($data) {
		if(isset($data["userId"]))
			$this->userId = $data["userId"];
		if(isset($data["ipAddress"]))
			$this->ipAddress = $data["ipAddress"];
		if(isset($data["userAgent"]))
			$this->userAgent = $data["userAgent"];
		if(isset($data["remember"]))
			$this->remember = $data["remember"];
		if(isset($data["sessionId"]))
			$this->sessionId = $data["sessionId"];
		if(isset($data["languageCode"]))
			$this->languageCode = $data["languageCode"];
	}
}

?>