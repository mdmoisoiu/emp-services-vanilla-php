<?php
/**
 * Request.php
 * Util to obtain information abbout HTTP request
 * Class common to all applications
 */

class Request {

    public static $REGISTRY_KEY = "Request";

    /**
     * Get the IF_MODIFIED_SINCE date (as a numerical timestamp) if available
     * @return int
     */
    function getIfModifiedSince() {
        if (!isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) return null;
        return strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
    }


    /**
     * Get the protocol used for the request (HTTP or HTTPS).
     * @return string
     */
    function getProtocol() {
        return (!isset($_SERVER['HTTPS']) || strtolower_codesafe($_SERVER['HTTPS']) != 'on') ? 'http' : 'https';
    }

    /**
     * Get the request method
     * @return string
     */
    function getRequestMethod() {
        $requestMethod = (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '');
        return $requestMethod;
    }

    /**
     * Get the remote IP address of the current request.
     * @return string
     */
    function getRemoteAddr() {
        static $ipaddr;
        if (!isset($ipaddr)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&
                preg_match_all('/([0-9.a-fA-F:]+)/', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            } else if (isset($_SERVER['REMOTE_ADDR']) &&
                preg_match_all('/([0-9.a-fA-F:]+)/', $_SERVER['REMOTE_ADDR'], $matches)) {
            } else if (preg_match_all('/([0-9.a-fA-F:]+)/', getenv('REMOTE_ADDR'), $matches)) {
            } else {
                $ipaddr = '';
            }

            if (!isset($ipaddr)) {
                // If multiple addresses are listed, take the last. (Supports ipv6.)
                $ipaddr = $matches[0][count($matches[0])-1];
            }
        }
        return $ipaddr;
    }

    /**
     * Get the user agent of the current request.
     * @return string
     */
    function getUserAgent() {
        if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if (!isset($userAgent) || empty($userAgent)) {
            $userAgent = getenv('HTTP_USER_AGENT');
        }
        if (!isset($userAgent) || $userAgent == false) {
            $userAgent = '';
        }
        return $userAgent;
    }


    /**
     * Get the value of a GET/POST variable.
     * @return mixed
     */
    function getUserVar($key) {
        // Get all vars (already cleaned)
        $vars = $this->getUserVars();

        if (isset($vars[$key])) {
            return $vars[$key];
        } else {
            return null;
        }
    }

    /**
     * Get all GET/POST variables as an array
     * @return array
     */
    function &getUserVars() {
        $requestVars = array_merge($_GET, $_POST);
        $this->cleanUserVar($requestVars);
        return $requestVars;
    }


    /**
     * Sanitize a user-submitted variable (i.e., GET/POST/Cookie variable).
     * Strips slashes if necessary, then sanitizes variable as per Core::cleanVar().
     * @param $var mixed
     */
    function cleanUserVar(&$var) {
        if (isset($var) && is_array($var)) {
            foreach ($var as $key => $value) {
                $this->cleanUserVar($var[$key]);
            }
        } else {
            return null;
        }
    }
}

?>