<?php
/**
 * CountryDAO.php
 * 
 */

include_once('BaseDAO.php');
include_once('Country.php');

class CountryDAO extends BaseDAO {

    public static $REGISTRY_KEY = "CountryDAO";

    /**
    * Constructor
    * @param PDO $da
    */
    function CountryDAO( & $da ) {
        parent::BaseDAO($da);
    }
    
    /**
     * 
     * @param int $id
     * @return Country|null
     */
    public function getCountryById($id){
        $country = null;
        if($id!=null){
            $result = $this->getCountries($id);
            if(count($result)==1){
                $country = $result[0];
            }
        }
        return $country;
    }
    
    /**
     * 
     * @param int $id
     * @return array of Country
     */
    public function getCountries($id = null, $code = null){
        $where = "WHERE 1";
        if($id!==null){
            $where .= sprintf(" AND id=%s", $this->da->quote($id));
        }
        if($code!==null){
            $where .= sprintf(" AND code=%s", $this->da->quote($code));
        }

        $sql = sprintf("SELECT 
                            id,
                            name,
                            code
                        FROM 
                            ed_country
                        %s",
                        $where
        );
        $rows = $this->da->query($sql);

        $countries = array();
        foreach($rows as $row) {
            $val = new Country();
            $val->id = $row["id"];
            $val->name = $row["name"];
            $val->code = $row["code"];

            $countries []= $val;
        }

        return $countries;
    }

}
?>