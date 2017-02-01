<?php
/**
 * SessionService.php
 * 
 */

include_once('User.php');
include_once('UserRoles.php');
include_once('Registry.php');
include_once('UserDAO.php');
include_once('BaseService.php');

class SessionService extends BaseService {


    /* ------------------------------- Access Part -------------------------------------*/
    /**
     * protected methods
     * @var array
     */
    public static $protectedMethods = array("getLoggedInUser");

    /**
     * function that use authentication plugin to get accepted roles for each function
     * @param String $methodName
     * @return array
     */
    public function _getMethodRoles($methodName) {
        // customization for specific methods allowed here
        if (in_array($methodName, self::$protectedMethods)) {
            return array(UserRoles::$BASIC_USER);
        } else {
            return null;
        }
    }
    /* ------------------------------- End Access Part -------------------------------------*/

    /**
     * setLanguage
     * @param string $languageCode
     * @return StdClass
     */
    public function setLanguage($languageCode){

        $this->sessionManager->setLanguageCode($languageCode);
        return (object) array(
            "result" => 1
        );
    }

    /**
     * login
     * @param StdClass $loginData
     * @return StdClass
     */
    public function login($loginData){
        $userDAO = Registry::get(UserDAO::$REGISTRY_KEY);
        $userId = $userDAO->getUserIdByCredentials($loginData->username, $loginData->password);
        if($userId>0){
            $this->sessionManager->setUserId($userId);
            $user = $userDAO->getUserById($userId);

            if (class_exists('AmfphpAuthentication')) {
                AmfphpAuthentication::addRole(UserRoles::$BASIC_USER);
                if($user && $user->role){
                    AmfphpAuthentication::addRole($user->role);
                }
            }
        }

        return (object) array(
            "result" => 1,
            "userId" => $userId
        );
    }

    /**
     * logout
     * @return StdClass
     */
    public function logout(){
        $this->sessionManager->setUserId(0);
        if (class_exists('AmfphpAuthentication')) {
            AmfphpAuthentication::clearSessionInfo();
        }
        $this->sessionManager->setUserId(0);
        return (object) array(
            "result" => 1
        );
    }

    /**
     * getLoggedInUser
     * @return StdClass
     */
    public function getLoggedInUser(){
        $userDAO = Registry::get(UserDAO::$REGISTRY_KEY);
        $userId = $this->sessionManager->getSessionVar(SessionVariables::$USER_ID);

        $user = null;
        if($userId>0){
            $user = $userDAO->getUserById($userId);
        }

        return (object) array(
            "result" => 1,
            "user" => $user
        );
    }
}

?>