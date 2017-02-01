<?php
/**
 * ImageDAO.php
 * @author Matei Moisoiu
 */

include_once('BaseDAO.php');

include_once('Image.php');

class ImageDAO extends BaseDAO {

    public static $REGISTRY_KEY = "ImageDAO";

    /**
    * Constructor
    * @param PDO $da
    */
    function ImageDAO( & $da ) {
        parent::BaseDAO($da);
    }
    
    /**
     * 
     * @param int $id
     * @return Image|null
     */
    public function getImageById($id){
        $image = null;
        if($id!=null){
            $result = $this->getImages($id);
            if(count($result)==1){
                $image = $result[0];
            }
        }
        return $image;
    }
    
    /**
     * 
     * @param int $id
     * @param int $employeeId
     * @return array of Image
     */
    public function getImages($id = null, $employeeId = null){
        $where = "WHERE 1";
        if($id!==null){
            $where .= sprintf(" AND id=%s", $this->da->quote($id));
        }
        if($employeeId!==null){
            $where .= sprintf(" AND employee_id=%s", $this->da->quote($employeeId));
        }

        $sql = sprintf("SELECT 
                            id,
                            file_name,
                            employee_id
                        FROM 
                            ed_image
                        %s
                        ORDER BY id ASC",
                        $where
        );
        $rows = $this->da->query($sql)->fetchAll();
        $images = array();
        foreach($rows as $row) {
            $val = new Image();
            $val->id = $row["id"];
            $val->fileName = $row["file_name"];
            $val->employeeId = $row["employee_id"];

            $images []= $val;
        }

        return $images;
    }
    
    /**
     *
     * @param int $employeeId
     * @return Image
     */
    public function getEmployeeImage($employeeId){
        $images = $this->getImages(null, $employeeId);
        if(count($images)>0){

            return $images[0];
        } else {
            return null;
        }
    }
    
    /**
     *
     * @param Image $image
     * @return boolean
     */
    public function insertImage(&$image){
        $sql = sprintf("INSERT INTO
                            ed_image
                         (
                            file_name,
                            employee_id
                        )
                        VALUES
                        (
                            %s,
                            %s
                        )",
            $this->da->quote($image->fileName),
            $this->da->quote($image->employeeId)
        );

        $insertResult = $this->da->exec($sql);
        $image->id = $this->da->lastInsertId();

        return $insertResult;
    }
    
    /**
     *
     * @param Image $image
     * @return boolean
     */
    public function updateImage($image){
        $sql = sprintf("UPDATE
                            ed_image
                        SET
                            file_name=%s,
                            employee_id=%s
                        WHERE 
                            id=%s",
            $this->da->quote($image->fileName),
            $this->da->quote($image->employeeId),
            $this->da->quote($image->id)
        );

        $res = $this->da->exec($sql);
        return $res;
    }
    
    /**
     *
     * @param int $valueId
     * @return boolean
     */
    public function deleteImage($valueId){
        $sql = sprintf("DELETE FROM
                            ed_image
                        WHERE 
                            id=%s",
            $this->da->quote($valueId)
        );
        $res = $this->da->exec($sql);
        return $res;
    }
}
?>