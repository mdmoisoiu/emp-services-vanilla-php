<?php
/**
 * CountryService.php
 * @author Matei Moisoiu
 */

include_once('BaseService.php');

include_once('Registry.php');

include_once('UserRoles.php');

class CountryService extends BaseService {


    /* ------------------------------- Access Part -------------------------------------*/
    /**
     * admin methods
     * @var array
     */
    public static $protectedMethods = array(
            "getCountries"
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
     * getCountries
     * @return StdClass with array of Country
     */
    public function getCountries(){
        /* @var $countryDAO CountryDAO */
        $countries = array();
        $result = 0;
        try {
            $countryDAO = Registry::get(CountryDAO::$REGISTRY_KEY);

            $countries = $countryDAO->getCountries();
            $result = 1;
        } catch (Exception $er){
            $this->logManager->logText($er);
        }

        return (object) array(
                "result" => $result,
                "countries" => $countries
        );
    }


}

?>