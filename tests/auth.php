<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 5/7/14
 * Time: 1:37 PM
 */
namespace tests;

class Auth{
    private $User = null;
    private $testuser = array();
    public function __construct(){
        $this->User = new \model\User();
    }
    public function run(){
        $this->generateUser();
        $r = $this->register();
        if(!$r){
            cld('Test stopped');
        }
        $r = $this->login();
        if(!$r){
            cld('Test stopped');
        }
        $r = $this->authByCode();
        if(!$r){
            cld('Test stopped');
        }
        $r = $this->removeUser();
        if(!$r){
            cld('Test stopped');
        }
    }

    private function generateUser(){
        $this->testuser['email'] = getRandomString(5, 2, 'lun') . '@testuser.com';
        $this->testuser['pass'] = getRandomString(7, 2, 'luns');
    }

    private function register(){
        cl('Registration test started.');
        cl("User: " . $this->testuser['email']. ' pass: ' . $this->testuser['pass']);
        $_GET['login'] = $this->testuser['email'];
        $_GET['pass'] = $this->testuser['pass'];
        $r = $this->User->register();
        if($r['ok']){
            cl('Registration completed successfully. User:');
            cl($this->User->getInfo());
            $this->testuser['_id'] = $r['user']['_id'];
        } else{
            cl('Registration failed. Errors:');
            cl($r['errors']);
            return false;
        }
        cl('-----------------------');
        return true;
    }

    private function login(){
        cl('Login test started');
        cl("User: " . $this->testuser['email'] . ' pass: ' . $this->testuser['pass']);
        $r = $this->User->login($this->testuser['email'], $this->testuser['pass']);
        if($r['ok']){
            cl('User logged. Session code: ' . $r['user']['sessioncode']);
            $this->testuser['usercode'] = $r['user']['sessioncode'];
            cl('User info:');
            cl($this->User->getInfo());
        } else{
            cl('Login failed. Errors: ');
            cl($r['errors']);
            return false;
        }
        cl('-----------------------');
        return true;
    }

    private function authByCode(){
        cl('Autologin test started.');
        cl('Usercode: '. $this->testuser['usercode']);
        $r = $this->User->authByCode($this->testuser['usercode']);
        if($r['ok']){
            cl('Login successful. User info:');
            cl($this->User->getInfo());
        } else{
            cl('No user found. Login failed.');
            return false;
        }
        cl('-----------------------');
        return true;
    }

    private function removeUser(){
        cl('Removing user.');
        $this->User->removeUser($this->testuser['_id']);
        if($this->User->userExists($this->testuser['_id'], $this->testuser['email'])){
            cl('User deleting failed.');
            $r = false;
        } else{
            cl('User removed successfully.');
            $r = true;
        }
        cl('-----------------------');
        return $r;
    }


}


