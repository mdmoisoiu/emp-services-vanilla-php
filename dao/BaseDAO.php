<?php
/**
 * BaseDAO.php
 * Base class to all DAOs
 * @author Matei Moisoiu
 */

class BaseDAO {

    /**
     * @var PDO $da
     */
    protected $da;
    /**
     * @var string $tableName
     */
    protected $tableName;
 
    /**
    * Constructor
    * @param PDO $da
    */
    function BaseDAO( & $da ) {
        $this->da = $da;
    }
    
    /**
     * Close connection
     */
    function __destruct() {
        $this->da = null;
    }     

}
?>