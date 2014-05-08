<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 5/6/14
 * Time: 1:35 PM
 */

namespace model;

class User {

    private $db = null;
    private $_id = null;
    private $name = null;
    private $email = null;
    private $pic = null;

    const COLLECTION = 'usres';

    public function __construct(){
        $this->db = new \MongoDBClient(DBNAME);
    }

    public function auth(){
        $result = array();
        if(cookie('usercode')){
            $result['type'] = 'autologin';
            $r = $this->authByCode(cookie('usercode'));
            $result['r'] = $r;
            $result['ok'] = $r['ok'];
        } elseif(get('login')){
            $result['type'] = 'login';
            $r = $this->login(get('login'), get('pass'));
            $result['r'] = $r;
            if(!$r['ok'] && $r['errors']['no_user_found']){
                $result['type'] = 'register';
                $r = $this->register();
                $result['r'] = $r;
                $result['ok'] = $r['ok'];
            }
        } else{
            $result['ok'] = false;
        }
        return $result;
    }

    public function register(){
        $result = array();
        $errors = array();
        if(!preg_match('#^\S+[@]\S+\.[a-z]{2,10}#', get('login'))){
            $errors['login'] = true;
        } else{
            $login = get('login');
        }
        if(strlen(get('pass')) < 3){
            $errors['short_pass'] = true;
        } else{
            $pass = get('pass');
        }
        if(count($errors)>0){
            $result['ok'] = false;
            $result['errors'] = $errors;
        } else{
            $user = array();
            $user['_id'] = $this->db->getNewId(self::COLLECTION, 5);
            $user['email'] = $login;
            $user['reg_ts'] = time();
            $user['salt'] = getRandomString(22, 5, 'luns');
            $user['pass'] = $this->getSalted($pass, $user['salt']);
            $user['his'] = array();
            $user['voted'] = array();
            $this->db->insert(self::COLLECTION, $user);
            $this->login($login, $pass);
            $result['user'] = $this->getInfo();
            $result['ok'] = true;
        }
        return $result;
    }

    public function login($email, $pass = ''){
        $result = array();
        $user = $this->db->findOne(self::COLLECTION, array('email' => $email));
        if($user && $user['pass'] == $this->getSalted($pass, $user['salt'])){
            $result['ok'] = true;
            $code = getRandomString(12, 5, 'lun');
            $this->db->update(self::COLLECTION, array('$set' => array('sessioncode' => $code)), array('_id' => $user['_id']));
            $user['sessioncode'] = $code;
            cookie('usercode', $code);
        } else{
            $result['ok'] = false;
            $result['errors'] = array();
            if(!$user['_id']){
                $result['errors']['no_user_found'] = true;
            } else{
                $result['errors']['password_not_match'] = true;
            }
        }
        if($result['ok']){
            $this->fillUserInfo($user);
        }
        $result['user'] = $this->getInfo();
        return $result;
    }

    public function authByCode($code){
        $result = array();
        $user = $this->db->findOne(self::COLLECTION, array('sessioncode' => $code));
        if($user){
            $result['ok'] = true;
            $this->fillUserInfo($user);
            $result['user'] = $this->getInfo();
        } else{
            cookie('usercode', '');
            $result['ok'] = false;
        }
        return $result;
    }

    private function getSalted($pass, $salt){
        return password_hash($pass, PASSWORD_BCRYPT, array('salt' => $salt));
    }

    public function getInfo($key = ''){
        $user = array(
            '_id' => $this->_id,
            'name' => $this->name,
            'email' => $this->email
        );
        if(!$key){
            return $user;
        } elseif(isset($user[$key])){
            return $user[$key];
        } else{
            return null;
        }
    }

    public function removeUser($_id){
        $this->db->remove(self::COLLECTION, array('_id' => $_id));
    }

    public function userExists($_id = null, $email = null){
        $condition = array('$or' =>array());
        if($_id){
            $condition['$or'][] = array('_id' => $_id);
        }
        if($email){
            $condition['$or'][] = array('email' => $email);
        }
        $count = $this->db->count(self::COLLECTION, $condition);
        return (boolean) $count;
    }

    private function fillUserInfo($user){
        $this->_id = $user['_id'];
        $this->email = $user['email'];
        $this->name = preg_replace('#^(\S+)[@]\S+$#','$1',$user['email']);
    }

    public function logout(){
        cookie('usercode', '');
    }


} 