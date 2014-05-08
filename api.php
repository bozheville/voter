<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 4/25/14
 * Time: 1:28 PM
 */

define('ROOT_PATH', dirname(__FILE__));
define('DEFAULT_CLASS_PATH', ROOT_PATH.  '/model/');
define('DBNAME', 'voter');
require_once ROOT_PATH . '/vendor/utils/main.php';
$utils = new DevUtils('fn', 'MongoDBClient');
spl_autoload_extensions(".php");
spl_autoload_register();

use model;

$App = new model\App();
$User = new model\User();
$User->auth();


class Api{
    private $App = null;
    private $User = null;
    private $output = array();

    public function __construct($type){
        $this->output['ok'] = true;
        $this->App = new model\App();
        $this->User = new model\User();
        $this->User->auth();
        $types = get_class_methods($this);
        if (in_array($type, $types)) {
            call_user_func(array($this, $type));
        } else {
            $this->output['ok']= false;
            $this->output['error'] = 'Wrong API Key';
        }
    }

    public function auth(){
        $r = $this->User->auth();
        $this->output['user'] = $this->User->getInfo();
        $this->output['ok'] = $r['r']['ok'];
    }

    public function logout(){
        $this->User->logout();
    }

    public function output(){
        echo json_encode($this->output);
    }
}

$Api = new Api(get('type'));
$Api ->output();