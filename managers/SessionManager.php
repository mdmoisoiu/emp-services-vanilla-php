<?php
/**
 * SessionManager.php
 * Class common to all applications
 */

include_once('SessionVariables.php');
include_once('Request.php');
include_once('Session.php');

class SessionManager {

    public static $REGISTRY_KEY = "SessionManager";

    /* @var $configManager ConfigManager */
    protected $configManager;

    /** @var Request */
    protected $request;

    /**
     * @param $request Request
     */
    function SessionManager($request) {
        $this->request = $request;

        session_start();
        $sessionId = session_id();
        
        // Create new session
        $this->userSession = new Session();
        $this->userSession->setDataFromArray(
            array(
                "sessionId" => $sessionId,
                "ipAddress" => $request->getRemoteAddr(),
                "userAgent" => $request->getUserAgent(),
                "languageCode" => $this->getSessionVar("languageCode"),
                "remember" => 1
            )
        );
    }

    /* --------------------------------- Session associated with the current request ---------------------------- */

    /**
     * Get the session associated with the current request.
     * @return Session
     */
    function getUserSession() {
        return $this->userSession;
    }

    /**
     * Set language code associated with session
     * @return void
     */
    function setLanguageCode($languageCode) {
        $this->userSession->languageCode = $languageCode;
        $this->setSessionVar(SessionVariables::$LANGUAGE_CODE, $languageCode);
    }

    /**
     * Set user id associated with session
     * @return void
     */
    function setUserId($userId) {
        $this->userSession->userId = $userId;
        $this->setSessionVar(SessionVariables::$USER_ID, $userId);
    }
    /* --------------------------------- Manage Session Variables ---------------------------- */

    /**
     * Get a session variable's value.
     * @param $key string
     * @return mixed
     */
    function getSessionVar($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Get a session variable's value.
     * @param $key string
     * @param $value mixed
     * @return mixed
     */
    function setSessionVar($key, $value) {
        $_SESSION[$key] = $value;
        return $value;
    }

    /**
     * Unset (delete) a session variable.
     * @param $key string
     */
    function unsetSessionVar($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    /* -------------------------------------------------------------------------------- */
}

?>