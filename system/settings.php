<?php
defined('_XSCMS_PFQ_SETTINGS') or die('ERROR');

 class SETTINGS
 {
 	protected  static  $mysql_host ='';
 	protected  static  $mysql_db   ='';
 	protected  static  $mysql_user ='';
 	protected  static  $mysql_pass ='';


 	protected static function getHost() { return self::$mysql_host; }
 	protected static function getDB()   { return self::$mysql_db; }
 	protected static function getUser() { return self::$mysql_user; }
 	protected static function getPass() { return self::$mysql_pass; }

}
?> 