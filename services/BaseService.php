<?php
/**
 * BaseService.php
 * Base class to all AMF Services
 */

include_once('Registry.php');
include_once('SessionManager.php');
include_once('LogManager.php');

class BaseService {
	
	/* @var $sessionManager SessionManager */
	protected $sessionManager;
	/* @var $logManager LogManager */
	protected $logManager;

	function BaseService() {
        $this->sessionManager = Registry::get(SessionManager::$REGISTRY_KEY);
        $this->logManager = Registry::get(LogManager::$REGISTRY_KEY);
	}

    function exitWithError($e){
       print $e->getMessage() . '.<br><br>';
       exit;
    }
}

?>