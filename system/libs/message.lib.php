<?php

class Message
{
    private static $instance = null;
    private static $register  = null;
    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __clone() {}
    private function __construct() {self::$register=new RegistryMessage(); }

    public function test()
    {
        var_dump($this);
    }

}


class RegistryMessage
{
       protected static $_registry = array();
}

?>