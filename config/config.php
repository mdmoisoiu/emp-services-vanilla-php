<?php

/**
 * site_config.php
 *
 * Configuration settings
 *
 */

#APP PATHS
define('BASE_DIR', '/users/mm/www/emp-services-vanilla-php/');
define('BASE_URL', 'http://localhost/emp-vp/');

//EXTERNAL
define('AMFPHP_SRC_DIR', BASE_DIR . 'amfphp-2.2/');

//DEPENDENT PATHS
define('APP_DIR', BASE_DIR . '');

define('SERVICES_BASE_CLASS_PATH', APP_DIR.'services/');
define('VO_BASE_CLASS_PATH', APP_DIR.'vos/');

define('IMAGES_DIR', BASE_DIR.'images/');
define('IMAGES_URL', BASE_URL.'images/');

// Set include path
set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR . 'dao/');
set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR . 'constants/');
set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR . 'managers/');
set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR . 'vos/');
set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR . 'services/');

?>

