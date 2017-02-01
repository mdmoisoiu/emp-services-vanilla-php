<?php
/**
 *  This file is part of amfPHP
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file license.txt.
 * @package Amfphp
 */

include_once("config/config.db.php");
include_once("config/config.php");
 
/**
*  includes
*  */
require_once AMFPHP_SRC_DIR . 'ClassLoader.php';

// 2.2 version
$config = new Amfphp_Core_Config();
$config->serviceFolders = array( SERVICES_BASE_CLASS_PATH );
$config->pluginsConfig['AmfphpVoConverter'] = array('voFolders' => array(VO_BASE_CLASS_PATH));

$gateway = Amfphp_Core_HttpRequestGatewayFactory::createGateway($config);
$gateway->service();
$gateway->output();


?>
