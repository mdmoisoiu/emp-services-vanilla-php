<?php
/**
 * @author Matei Moisoiu
 *
 * EmployeeDAO.php
 *
 */

include_once('BaseDAO.php');

include_once('Employee.php');

include_once('ImageDAO.php');
include_once('Registry.php');

class EmployeeDAO extends BaseDAO {

    public static $REGISTRY_KEY = "EmployeeDAO";

    /**
    * Constructor
    * @param PDO $da
    */
    function EmployeeDAO( & $da ) {
        parent::BaseDAO($da);
    }
    
    /**
     * 
     * @param int $id
     * @return Employee|null
     */
    public function getEmployeeById($id){
        $employee = null;
        if($id!=null){
            $result = $this->getEmployees($id);
            if(count($result)==1){
                $employee = $result[0];
            }
        }
        return $employee;
    }
    
    /**
     * 
     * @param int $id
     * @return array of Employee
     */
    public function getEmployees($id = null, $target = null){
        $imageDAO = Registry::get(ImageDAO::$REGISTRY_KEY);

        $where = "WHERE 1";
        if($id!==null){
            $where .= " AND id=:id";
        }

        $sql = sprintf("SELECT 
                            id,
                            first_name,
                            last_name,
                            email,
                            phone
                        FROM 
                            ed_employee
                        %s",
                        $where
        );
        $statement = $this->da->prepare($sql);
        $statement->bindParam(":id",$id);

        $statement->execute();
        $rows = $statement->fetchAll();

        $employees = array();
        foreach($rows as $row) {
            $val = new Employee();
            $val->id = $row["id"];
            $val->firstName = $row["first_name"];
            $val->lastName = $row["last_name"];
            $val->email = $row["email"];
            $val->phone = $row["phone"];

            $image = $imageDAO->getEmployeeImage($val->id);
            if($image){
                $val->imageId = $image->id;
                $val->imageUrl = IMAGES_URL . $image->id.'_'.$image->fileName;
            }

            $employees []= $val;
        }

        return $employees;
    }
    
    /**
     *
     * @return int the number of employees
     */
    public function getEmployeesNumber(){
        $sql = sprintf("SELECT
                            COUNT(*) as employees_number
                        FROM
                            ed_employee"
        );
        $rows = $this->da->query($sql);
        $row = $rows->fetch();
        if($row){
            return $row["employees_number"];
        }
        return 0;
    }
    
    /**
     *
     * @return bool check if entry exists in table
     */
    public function checkIfEmployeeExists($id){
        $sql = sprintf("SELECT
                            id
                        FROM
                            ed_employee
                        WHERE id=%s",
            $this->da->quote($id)
        );
        $rows = $this->da->query($sql);
        $row = $rows->fetch();
        if($row){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     *
     * @return int
     */
    public function reserveEmployeeId(){
        $sql = sprintf("START TRANSACTION;
                        INSERT INTO ed_employee() VALUES ();
                        ROLLBACK;"
        );
        $this->da->query($sql);

        $sql = sprintf("SELECT LAST_INSERT_ID() as last_id");
        $rows = $this->da->query($sql);
        $row = $rows->fetch();
        if($row){
            return $row["last_id"];
        }
        return 0;
    }
    
    
    /**
     *
     * @return array of Employee
     */
    public function getEmployeesIdMap(){
        $employees = $this->getEmployees();
        $employeesIdMap = array();
    
        foreach ($employees as $employee){
            $employeesIdMap[$employee->id] = $employee;
        }
        return $employeesIdMap;
    }
    
    /**
     *
     * @param Employee $employee
     * @return boolean
     */
    public function insertEmployee(&$employee){
        $sql = sprintf("INSERT INTO
                            ed_employee
                         (
                            id,
                            first_name,
                            last_name,
                            email,
                            phone
                        )
                        VALUES
                        (
                            %s,
                            %s,
                            %s,
                            %s,
                            %s
                        )",
            $this->da->quote($employee->id),
            $this->da->quote($employee->firstName),
            $this->da->quote($employee->lastName),
            $this->da->quote($employee->email),
            $this->da->quote($employee->phone)
        );

        $insertResult = $this->da->exec($sql);
        $employee->id = $this->da->lastInsertId();

        return $insertResult;
    }
    
    /**
     *
     * @param Employee $employee
     * @return boolean
     */
    public function updateEmployee($employee){
        $sql = sprintf("UPDATE
                            ed_employee
                        SET
                            first_name=%s,
                            last_name=%s,
                            email=%s,
                            phone=%s
                        WHERE 
                            id=%s",
            $this->da->quote($employee->firstName),
            $this->da->quote($employee->lastName),
            $this->da->quote($employee->email),
            $this->da->quote($employee->phone),
            $this->da->quote($employee->id)
        );
//        var_dump($sql); die();

        $res = $this->da->exec($sql);
        return $res;
    }
    
    /**
     *
     * @param int $valueId
     * @return boolean
     */
    public function deleteEmployee($valueId){
        $sql = sprintf("DELETE FROM
                            ed_employee
                        WHERE 
                            id=%s",
            $this->da->quote($valueId)
        );
        $res = $this->da->exec($sql);
        return $res;
    }   

}
?>