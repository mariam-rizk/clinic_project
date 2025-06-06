<?php

namespace App\core;

class Session{

    public static function start(){
        session_start();
    }


    public static function set($key , $value){
        $_SESSION[$key] = $value;

    }

    public static function get($key){
        return $_SESSION[$key] ?? null; 
    }

    public static function flash($key){
       if(isset($_SESSION[$key])){
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
       }
        return null;
    }


    public static function remove($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        
    }

    public static function removeAll(){
        session_unset();
        session_destroy();
    }

    public static function getAll(){
        return $_SESSION;
    }


    public static function has($key){
        return isset($_SESSION[$key]);
    }
}