<?php
/**
 * EmployeeService.php
 * 
 */

include_once('BaseService.php');

include_once('ChatMessageDAO.php');

include_once('UserRoles.php');

include_once('Registry.php');
include_once('SessionVariables.php');

class ChatService extends BaseService {


    /* ------------------------------- Access Part -------------------------------------*/
    /**
     * admin methods
     * @var array
     */
    public static $protectedMethods = array(
            "getMessages",
            "postMessage"
    );

    /**
     * function that use authentication plugin to get accepted roles for each function
     * @param String $methodName
     * @return array
    */
    public function _getMethodRoles($methodName) {
        if (in_array($methodName, self::$protectedMethods)) {
            return array(UserRoles::$BASIC_USER);
        } else {
            return null;
        }
    }
    /* ------------------------------- End Access Part -------------------------------------*/

    /**
     * getEmployees
     * @return StdClass with array of ChatMessage
     */
    public function getMessages($lastId){
        /* @var $chatMessageDAO ChatMessageDAO */

        $result = 0;
        try {
            $chatMessageDAO = Registry::get(ChatMessageDAO::$REGISTRY_KEY);

            if($lastId!=null){
                $chatMessages = $chatMessageDAO->getChatMessages(null, $lastId);
            } else {
                $chatMessages = $chatMessageDAO->getChatMessages(null, null, 20);
            }

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "chatMessages" => $chatMessages
        );
    }

    /**
     * postMessage
     * @param String $message
     * @return StdClass with a Employee
     */
    public function postMessage($message){
        /* @var $chatMessageDAO ChatMessageDAO */
        $result = 0;
        try {
            $chatMessageDAO = Registry::get(ChatMessageDAO::$REGISTRY_KEY);

            $chatMessage = new ChatMessage();
            $chatMessage->message = $message;
            $chatMessage->userId = $this->sessionManager->getSessionVar(SessionVariables::$USER_ID);

            $chatMessageDAO->insertChatMessage($chatMessage);
            $chatMessage = $chatMessageDAO->getChatMessageById($chatMessage->id);

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "chatMessage" => $chatMessage
        );
    }
}

?>