<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 5/7/14
 * Time: 1:38 PM
 */
define('DEFAULT_CLASS_PATH', ROOT_PATH.  '/model/');
define('DBNAME', 'voter');
require_once ROOT_PATH . '/vendor/utils/main.php';
$utils = new DevUtils('fn', 'MongoDBClient');
spl_autoload_extensions(".php");
spl_autoload_register();

define('ROOT_PATH', dirname(__FILE__));
require_once ROOT_PATH . '/include/settings.php';