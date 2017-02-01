<?php
/**
 * PositionService.php
 * @author Matei Moisoiu
 */

include_once('BaseService.php');

include_once('PositionDAO.php');

include_once('UserRoles.php');

include_once('PositionManager.php');
include_once('Registry.php');

class PositionService extends BaseService {


    /* ------------------------------- Access Part -------------------------------------*/
    /**
     * admin methods
     * @var array
     */
    public static $protectedMethods = array(
            "getPositions",
            "getPosition",
            "savePosition",
            "setPositionEmployee",
            "deletePosition"
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
     * getPositions
     * @return StdClass with array of Position
     */
    public function getPositions(){
        /* @var $positionDAO PositionDAO */
        /* @var $positionManager PositionManager */
        $positions = array();
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);
            $positionManager = Registry::get(PositionManager::$REGISTRY_KEY);

            $positions = $positionManager->sortPositionsAsOrgChart($positionDAO->getPositions());



            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "positions" => $positions
        );
    }

    /**
     * getPosition
     * @param int $id
     * @return StdClass with a Position
     */
    public function getPosition($id){
        /* @var $positionDAO PositionDAO */
        $position = null;
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);

            $position = $positionDAO->getPositionById($id);
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "position" => $position
        );
    }

    /**
     * getPositionsNumber
     * @return StdClass with number of positions
     */
    public function getPositionsNumber(){
        /* @var $positionDAO PositionDAO */
        $positionsNumber = 0;
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);

            $positionsNumber = $positionDAO->getPositionsNumber();
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "positionsNumber" => $positionsNumber
        );
    }

    /**
     * reservePositionId
     * @return StdClass with a positionId
     */
    public function reservePositionId(){
        /* @var $positionDAO PositionDAO */
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);
            $positionId = $positionDAO->reservePositionId();

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "positionId" => $positionId
        );
    }

    /**
     * savePosition
     * @param Position $position
     * @return StdClass with a Position
     */
    public function savePosition($position){
        /* @var $positionDAO PositionDAO */
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);

            if($positionDAO->checkIfPositionExists($position->id)){
                $positionDAO->updatePosition($position);
            } else {
                $positionDAO->insertPosition($position);
            }
            $positionDAO->setPositionEmployee($position->id, $position->employeeId);

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "position" => $position
        );
    }

    /**
     * setPositionEmployee
     * @param int $positionId
     * @param int $employeeId
     * @return StdClass with a result boolean
     */
    public function setPositionEmployee($positionId, $employeeId){
        /* @var $positionDAO PositionDAO */
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);

            $positionDAO->setPositionEmployee($positionId, $employeeId);
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result
        );
    }

    /**
     * deletePosition
     * @param int $positionId
     * @return StdClass with a result boolean
     */
    public function deletePosition($positionId){
        /* @var $positionDAO PositionDAO */
        $result = 0;
        try {
            $positionDAO = Registry::get(PositionDAO::$REGISTRY_KEY);

            $positionDAO->deletePosition($positionId);
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result
        );
    }
}

?>