<?php
/**
 * UserDAO.php
 * @author Matei Moisoiu
 */

include_once('BaseDAO.php');

include_once('User.php');

class UserDAO extends BaseDAO {

    public static $REGISTRY_KEY = "UserDAO";

    /**
    * Constructor
    * @param DbAccess $da
    */
    function UserDAO( & $da ) {
        parent::BaseDAO($da);
        $this->tableName = "app_user";
    }
    
    /**
     * 
     * @param int $id
     * @return User|null
     */
    public function getUserById($id){
        $user = null;
        if($id!=null){
            $result = $this->getUsers($id);
            if(count($result)==1){
                $user = $result[0];
            }
        }
        return $user;
    }
    
    /**
     * 
     * @param int $id
     * @return array of User
     */
    public function getUsers($id = null){
        $where = "WHERE 1";
        if($id!==null){
            $where .= sprintf(" AND id=%s", $this->da->quote($id));
        }

        $sql = sprintf("SELECT 
                            id,
                            first_name,
                            last_name,
                            email,
                            username,
                            password,
                            role
                        FROM app_user
                        %s",
                        $where
        );
        $rows = $this->da->query($sql);

        $users = array();
        foreach($rows as $row) {
            $val = new User();
            $val->id = $row["id"];
            $val->firstName = $row["first_name"];
            $val->lastName = $row["last_name"];
            $val->email = $row["email"];
            $val->username = $row["username"];
            $val->password = $row["password"];
            $val->role = $row["role"];

            $users []= $val;
        }

        return $users;
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return number
     */
    public function getUserIdByCredentials($username, $password){
        $sql = sprintf("SELECT id
                        FROM app_user
                        WHERE (LOWER(email)=%s OR LOWER(username)=%s) AND password=%s",
                        $this->da->quote(strtolower($username)),
                        $this->da->quote(strtolower($username)),
                        $this->da->quote($password)
        );
        $rows = $this->da->query($sql);
        $row = $rows->fetch();

        if ($row) {
            return $row['id'];
        }
        return 1;
    }
}
?>