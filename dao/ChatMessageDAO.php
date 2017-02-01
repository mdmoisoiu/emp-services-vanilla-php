<?php
/**
 * ChatMessageDAO.php
 * 
 */

include_once('BaseDAO.php');

include_once('ChatMessage.php');

class ChatMessageDAO extends BaseDAO {

    public static $REGISTRY_KEY = "ChatMessageDAO";

    /**
    * Constructor
    * @param PDO $da
    */
    function ChatMessageDAO( & $da ) {
        parent::BaseDAO($da);
    }
        
    /**
     *
     * @param int $id
     * @return ChatMessage|null
     */
    public function getChatMessageById($id){
        $chatMessage = null;
        if($id!=null){
            $result = $this->getChatMessages($id);
            if(count($result)==1){
                $chatMessage = $result[0];
            }
        }
        return $chatMessage;
    }
    
    /**
     * 
     * @param int $id
     * @return array of ChatMessage
     */
    public function getChatMessages($id = null, $lastId = null, $rowsLimit = null){
        $where = "WHERE 1";
        $limit = "";
        if($id!==null){
            $where .= sprintf(" AND chat.id=%s", $this->da->quote($id));
        }
        if($lastId!==null){
            $where .= sprintf(" AND chat.id>%s", $this->da->quote($lastId));
        }
        if($rowsLimit!==null){
            $limit = sprintf("LIMIT %s", intval($rowsLimit));
        }


        $sql = sprintf("SELECT 
                            chat.id as id,
                            chat.user_id as user_id,
                            CONCAT(usr.first_name, ' ', usr.last_name) as user_name,
                            chat.message as message,
                            chat.date_time as date_time
                        FROM 
                            ed_chat as chat
                        JOIN app_user as usr ON chat.user_id=usr.id
                        %s
                        ORDER BY chat.id DESC
                        %s",
                        $where,
                        $limit
        );
        $rows = $this->da->query($sql)->fetchAll();
        $chatEntries = array();
        foreach($rows as $row) {
            $val = new ChatMessage();
            $val->id = $row["id"];
            $val->userId = $row["user_id"];
            $val->userName = $row["user_name"];
            $val->message = $row["message"];
            $val->dateTime = $row["date_time"];

            $chatEntries []= $val;
        }

        return $chatEntries;
    }
    
    /**
     *
     * @param ChatMessage $chatMessage
     * @return boolean
     */
    public function insertChatMessage(&$chatMessage){
        $sql = sprintf("INSERT INTO
                            ed_chat
                         (
                            user_id,
                            message,
                            date_time
                        )
                        VALUES
                        (
                            %s,
                            %s,
                            NOW()
                        )",
            $this->da->quote($chatMessage->userId),
            $this->da->quote($chatMessage->message)
        );

        $insertResult = $this->da->exec($sql);
        $chatMessage->id = $this->da->lastInsertId();

        return $insertResult;
    }

}
?>