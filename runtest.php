<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 5/7/14
 * Time: 1:43 PM
 */
define('ROOT_PATH', dirname(__FILE__));
require_once ROOT_PATH . '/include/settings.php';
//use model;
//use tests;

$Test = new tests\Auth();
$Test->run();