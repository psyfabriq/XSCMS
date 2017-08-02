<?php
class Registry 
{	
    static protected $data = array();
    static protected $lock = array();
    static public function set($key, $value) {
        if ( !self::hasLock($key) ) {
            self::$data[$key] = $value;
        } else {
            throw new Exception("переменная '$key' заблокирована для изменений");
        }
    }
    static public function get($key, $default = null, $clean = FALSE) {
        if ( self::has($key) ) {
            if ($clean==TRUE){return str_replace("../","",self::$data[$key]);}
            else{             return self::$data[$key];}
        } else {
            return $default;
        }
    }

    static public function gete($key, $default = null, $clean = FALSE) {
        if ( self::has($key) ) {
            if ($clean==TRUE){echo str_replace("../","",self::$data[$key]);}
            else{             echo self::$data[$key];}
        } else {
            echo  $default;
        }
    }

    static public function remove($key) {
        if ( self::has($key) && self::hasLock($key) ) {
            unset(self::$data[$key]);
        }
    }
    static public function has($key) {
        return isset(self::$data[$key]);
    }
    static public function lock($key) {
        self::$lock[$key] = true;
    }
    static public function hasLock($key) {
        return isset(self::$lock[$key]);
    }
    static public function unlock($key) {
        if ( self::hasLock($key) ) {
          unset(self::$lock[$key]);
        }
    }
}	
?>