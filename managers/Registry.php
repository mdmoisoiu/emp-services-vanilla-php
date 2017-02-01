<?php
/**
 * Registry
 * @author Matei Moisoiu
 */

include_once('Registry.php');
include_once('Request.php');

include_once('CountryDAO.php');
include_once('EmployeeDAO.php');
include_once('ImageDAO.php');
include_once('PositionDAO.php');
include_once('ChatMessageDAO.php');
include_once('UserDAO.php');

include_once('PositionManager.php');
include_once('SessionManager.php');
include_once('LogManager.php');

class Registry {

    public static $PDO_REGISTRY_KEY = "PDO";
    protected static $registry;

    static function getRegistry() {
        if(is_null(Registry::$registry)){
            Registry::$registry = array();
        }
        return Registry::$registry;
    }
    
    /**
     * @param $key string
     * @return mixed
     */
    static function get($key) {
        $res = null;
        if(!$res){
            $registry = Registry::getRegistry();
            switch($key){
                case self::$PDO_REGISTRY_KEY:
                    $res = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
                    break;
                case SessionManager::$REGISTRY_KEY:
                    $request = Registry::get(Request::$REGISTRY_KEY);
                    $res = new SessionManager($request);
                    break;
                case Request::$REGISTRY_KEY:
                    $res = new Request();
                    break;
                case LogManager::$REGISTRY_KEY:
                    $res = new LogManager();
                    break;
                case CountryDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new CountryDAO($da);
                    break;
                case EmployeeDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new EmployeeDAO($da);
                    break;
                case ImageDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new ImageDAO($da);
                    break;
                case PositionDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new PositionDAO($da);
                    break;
                case ChatMessageDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new ChatMessageDAO($da);
                    break;
                case UserDAO::$REGISTRY_KEY:
                    $da = Registry::get(Registry::$PDO_REGISTRY_KEY);
                    $res = new UserDAO($da);
                    break;
                case PositionManager::$REGISTRY_KEY:
                    $res = new PositionManager();
                    break;
            }
            $registry[$key] = $res;
        }
        return $res;
    }


    /**
     * @param $key string
     * @param $value mixed
     */
    static function set($key, &$value) {
        if(is_null(Registry::$registry)){
            Registry::$registry = array();
        }
        Registry::$registry[$key] = $value;
    }

    /**
     * @param $key string
     * @return boolean
     */
    static function has($key) {
        if(is_null(Registry::$registry)){
            Registry::$registry = array();
        }
        return isset(Registry::$registry[$key]);
    }

    /**
     * @param $key string
     */
    static function delete($key) {
        if(is_null(Registry::$registry)){
            Registry::$registry = array();
        }
        if (isset(Registry::$registry[$key])) {
            unset(Registry::$registry[$key]);
        }
    }
}

?>