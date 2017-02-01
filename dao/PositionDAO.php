<?php
/**
 * PositionDAO.php
 * 
 */

include_once('BaseDAO.php');

include_once('Position.php');

class PositionDAO extends BaseDAO {

	public static $REGISTRY_KEY = "PositionDAO";
	
    /**
    * Constructor
	* @param PDO $da 
    */
    function PositionDAO( & $da ) {
    	parent::BaseDAO($da);
    }
    
    /**
     * 
     * @param int $id
     * @return Position|null
     */
    public function getPositionById($id){
    	$position = null;
    	if($id!=null){
    		$result = $this->getPositions($id);
    		if(count($result)==1){
    			$position = $result[0];
    		}
    	}
    	return $position;
    }
    
    /**
     * 
     * @param int $id
     * @return array of Position
     */
    public function getPositions($id = null, $target = null){
    	$where = "WHERE 1";
    	if($id!==null){
    		$where .= " AND pos.id=:positionId";
    	}
    	
    	$sql = sprintf("SELECT 
    						pos.id,
    						pos.line_manager_id,
    						pos.country_id,
    						pos.name,
    						ehp.employee_id,
    						CONCAT(emp.first_name, emp.last_name) as employee_name,
    						ctr.name as country_name
						FROM 
    						ed_position as pos
    					LEFT JOIN ed_position_has_employee as ehp ON pos.id=ehp.position_id 
    					LEFT JOIN ed_employee as emp ON emp.id=ehp.employee_id 
    					LEFT JOIN ed_country as ctr ON pos.country_id=ctr.id 
						%s
    					ORDER BY pos.name",
    					$where
    	);
        $statement = $this->da->prepare($sql);
        $statement->bindParam(":positionId", $id, PDO::PARAM_INT);

        $statement->execute();
    	$rows = $statement->fetchAll();
    	
    	$positions = array();
    	foreach($rows as $row) {
    		$val = new Position();
    		$val->id = $row["id"];
    		$val->lineManagerId = $row["line_manager_id"];
    		$val->countryId = $row["country_id"];
    		$val->name = $row["name"];
    		$val->employeeId = is_null($row["employee_id"]) ? 0 : $row["employee_id"];
    		$val->employeeName = is_null($row["employee_name"]) ? 0 : $row["employee_name"];
    		$val->countryName = is_null($row["country_name"]) ? "" : $row["country_name"];
    	
    		$positions []= $val;
    	}

    	return $positions;
    }
    
    /**
     *
     * @return int the number of positions
     */
    public function getPositionsNumber(){
    	$sql = sprintf("SELECT
    						COUNT(*) as positions_number
						FROM
    						ed_position"
    	);
    	$rows = $this->da->query($sql);
    	$row = $rows->fetch();
    	if($row){
    		return $row["positions_number"];
    	}
    	return 0;
    }
    
    /**
     *
     * @return bool check if entry exists in table
     */
    public function checkIfPositionExists($id){
    	$sql = sprintf("SELECT
    						id
						FROM
    						ed_position
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
    public function reservePositionId(){   	 
    	$sql = sprintf("START TRANSACTION;
						INSERT INTO ed_position() VALUES ();
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
     * @return array of Position
     */
    public function getPositionsIdMap(){
    	$positions = $this->getPositions();
    	$positionsIdMap = array();
    
    	foreach ($positions as $position){
    		$positionsIdMap[$position->id] = $position;
    	}
    	return $positionsIdMap;
    }
    
    
    /**
     *
     * @param Position $position
     * @return boolean
     */
    public function insertPosition(&$position){

        $sql = "INSERT INTO
                    ed_position
                 (
                    id,
                    line_manager_id,
                    country_id,
                    name
                )
                VALUES
                (
                    :id,
                    :lineManagerId,
                    :countryId,
                    :name
                )";

        $statement = $this->da->prepare($sql);
        $statement->bindParam(":id", $position->id, PDO::PARAM_INT);
        $statement->bindParam(":lineManagerId",$position->lineManagerId, PDO::PARAM_INT);
        $statement->bindParam(":countryId",$position->countryId, PDO::PARAM_INT);
        $statement->bindParam(":name", $position->name, PDO::PARAM_STR);

    	$insertResult = $statement->execute();
    	$position->id = $this->da->lastInsertId();

    	return $insertResult;
    }
    
    /**
     *
     * @param Position $position
     * @return boolean
     */
    public function updatePosition($position){
    	$sql = "UPDATE
                    ed_position
                SET
                    line_manager_id=:lineManagerId,
                    country_id=:countryId,
                    name=:name
                WHERE 
                    id=:positionId";

        $statement = $this->da->prepare($sql);
        $statement->bindParam(":positionId", $position->id, PDO::PARAM_INT);
        $statement->bindParam(":lineManagerId",$position->lineManagerId, PDO::PARAM_INT);
        $statement->bindParam(":countryId",$position->countryId, PDO::PARAM_INT);
        $statement->bindParam(":name", $position->name, PDO::PARAM_STR);


        $res = $statement->execute();
    	return $res;
    }
    
    /**
     *
     * @param int $positionId
     * @return boolean
     */
    public function deletePosition($positionId){
    	$sql = "DELETE FROM
                    ed_position
                WHERE 
                    id=:positionId";

        $statement = $this->da->prepare($sql);
        $statement->bindParam(":positionId", $positionId, PDO::PARAM_INT);

    	$res = $statement->execute();
    	return $res;
    }
    
    /**
     *
     * @param int $positionId
     * @param int $employeeId
     */
    public function setPositionEmployee($positionId, $employeeId){
    	$this->removePositionEmployee($positionId);
    	$this->insertPositionEmployee($positionId, $employeeId);
    }
    
    /**
     *
     * @param int $positionId
     * @param int $employeeId
     * @return boolean
     */
    public function insertPositionEmployee($positionId, $employeeId){
    	$sql = "INSERT INTO
                    ed_position_has_employee
                 (
                    position_id,
                    employee_id
                )
                VALUES
                (
                    :positionId,
                    :employeeId
                )";

        $statement = $this->da->prepare($sql);
        $statement->bindParam(":positionId", $positionId, PDO::PARAM_INT);
        $statement->bindParam(":employeeId", $employeeId, PDO::PARAM_INT);

        $res = $statement->execute();
    	return $res;
    }
    
    /**
     *
     * @param int $positionId
     * @return boolean
     */
    public function removePositionEmployee($positionId){
    	$sql = "DELETE FROM
                    ed_position_has_employee
                WHERE
                    position_id=:positionId";
        $statement = $this->da->prepare($sql);
        $statement->bindParam(":positionId", $positionId, PDO::PARAM_INT);

    	$res = $statement->execute();
        return $res;
    }
}
?>