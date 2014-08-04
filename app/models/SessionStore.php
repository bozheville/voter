<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 7/30/14
 * Time: 12:31 PM
 */


class SessionStore {


    public static $instance = null;

    private function __construct() {



    }

//    protected function __clone()
//    {
//        //Me not like clones! Me smash clones!
//    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

}