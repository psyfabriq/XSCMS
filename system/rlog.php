<?php
class RLog 
{	
    static protected $data = array();
    static protected $lock = array();
    static public function set($key, $value) {
        if ( !self::hasLock($key) ) {
            self::$data[$key] = $value;
        } else {
            throw new Exception("переменная '$key' заблокирована для изменений");
        }

        //var_dump(self::$data);
    }
    static public function get($key) {
        if ( self::has($key) ) {
            return self::$data[$key];
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
    static public function write() {
        //$logsFolder = substr(Registry::get('Logs',null,true), 0, -1).'/';
        $tmp = '';
        foreach (self::$data as $value ) {
            $tmp .= $value . '<br>';
        }
        //var_dump($data);
        return $tmp;
    }
}   
?>