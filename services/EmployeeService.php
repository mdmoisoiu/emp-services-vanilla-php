<?php
/**
 * @author Matei Moisoiu
 *
 * EmployeeService.php
 *
 */

include_once('BaseService.php');

include_once('EmployeeDAO.php');

include_once('UserRoles.php');

include_once('Registry.php');

class EmployeeService extends BaseService {


    /* ------------------------------- Access Part -------------------------------------*/
    /**
     * admin methods
     * @var array
     */
    public static $protectedMethods = array(
            "getEmployees",
            "getEmployee",
            "saveEmployee",
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
     * @return StdClass with array of Employee
     */
    public function getEmployees(){
        /* @var $employeeDAO EmployeeDAO */
        $employees = array();
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);

            $employees = $employeeDAO->getEmployees();
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "employees" => $employees
        );
    }

    /**
     * getEmployeesNumber
     * @return StdClass with number of employees
     */
    public function getEmployeesNumber(){
        /* @var $employeeDAO EmployeeDAO */
        $employeesNumber = 0;
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);

            $employeesNumber = $employeeDAO->getEmployeesNumber();
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "employeesNumber" => $employeesNumber
        );
    }

    /**
     * getEmployee
     * @param int $id
     * @return StdClass with a Employee
     */
    public function getEmployee($id){
        /* @var $employeeDAO EmployeeDAO */
        $employee = null;
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);

            $employee = $employeeDAO->getEmployeeById($id);
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "employee" => $employee
        );
    }

    /**
     * reserveEmployeeId
     * @return StdClass with a employeeId
     */
    public function reserveEmployeeId(){
        /* @var $employeeDAO EmployeeDAO */
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);
            $employeeId = $employeeDAO->reserveEmployeeId();

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "employeeId" => $employeeId
        );
    }

    /**
     * saveEmployee
     * @param Employee $employee
     * @return StdClass with a Employee
     */
    public function saveEmployee($employee){
        /* @var $employeeDAO EmployeeDAO */
        /* @var $imageDAO ImageDAO */
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);
            $imageDAO = Registry::get(ImageDAO::$REGISTRY_KEY);

            if($employeeDAO->checkIfEmployeeExists($employee->id)){
                $employeeDAO->updateEmployee($employee);
            } else {
                $employeeDAO->insertEmployee($employee);
            }

            $images = $imageDAO->getImages(null, $employee->id);
            foreach ($images as $image){
                if($image->id!=$employee->imageId){
                    unlink(IMAGES_DIR.$image->id.'_'.$image->fileName);
                    $imageDAO->deleteImage($image->id);
                }
            }

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "employee" => $employee
        );
    }

    /**
     * saveEmployeePicture
     * @param Image $image
     * @return StdClass with a result boolean
     */
    public function saveEmployeePicture($image){
        /* @var $imageDAO ImageDAO */
        $result = 0;
        try {
            $imageDAO = Registry::get(ImageDAO::$REGISTRY_KEY);

            $image->fileName = $this->normal_chars($image->fileName);
            $image->fileData = base64_decode($image->fileData);

            $imageDAO->insertImage($image);
            file_put_contents(IMAGES_DIR. $image->id.'_'.$image->fileName, $image->fileData);
            $image->fileData = null;
            $imageUrl = IMAGES_URL . $image->id.'_'.$image->fileName;

            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "image" => $image,
                "imageUrl" => $imageUrl
        );
    }

    /**
     * deleteEmployee
     * @param int $employeeId
     * @return StdClass with a result boolean
     */
    public function deleteEmployee($employeeId){
        /* @var $employeeDAO EmployeeDAO */
        $result = 0;
        try {
            $employeeDAO = Registry::get(EmployeeDAO::$REGISTRY_KEY);

            $employeeDAO->deleteEmployee($employeeId);
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result
        );
    }

    protected function normal_chars($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace(array('~[^0-9a-z.]~i', '~[ -]+~'), ' ', $string);

        return trim($string, ' -');
    }
}

?>