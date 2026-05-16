<?php

namespace Framework;

class Session {
    /**
     * start session
     * 
     * @return void
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * set session value
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     * 
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * 
     * get value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     */
    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * 
     * check if session exists
     * 
     * @param string $key
     * @return bool
     * 
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * clear session
     * 
     * @param string $key
     * @return void
     * 
     */
    public static function clear($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * clear session
     * 
     * @return void
     * 
     */
    public static function clearAll() {
        session_unset();
        session_destroy();
    }
}