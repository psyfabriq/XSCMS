<?php
defined('_XSCMS_PFQ_DB') or die('ERROR');
define('_XSCMS_PFQ_SETTINGS', 1);

error_reporting( E_ERROR );

include("./system/settings.php");
include('./system/libs/include.classloader.php');

$classLoader->addToClasspath('system/libs/JSONtoMYSQL/');


abstract class DBConnection extends SETTINGS{
    private static $conn;
    private static $context;

    public static function init(XSContext $context) {
      self::$context=$context;
    	$result=false;
        if(self::$conn===null){

          try {
            $mysqli = mysqli_init();
            if (!$mysqli) {
                  $result=false;
              }
              else{
                     $mysqli-> options (MYSQLI_OPT_CONNECT_TIMEOUT, 5);

                     if (!$mysqli->real_connect(parent::getHost(), parent::getUser(),parent::getPass(), parent::getDB())) {
                            $result=false;
                            return $result;
                        }
                        else{self::$conn = new mysqli(parent::getHost(), parent::getUser(), parent::getPass(), parent::getDB());}
               }

              }
           catch(Exception $e){$result=false;}

        	if (self::$conn->connect_error) {
        		 $result=false;
        	}
        	else{
        		 $result=true;
                 self::$conn->query("SET CHARACTER SET 'utf8'");
        	}
        }else{ $result=true; }

        if($result===true){
          Registry::set('DBOBJ', self::$conn);
          Registry::lock('DBOBJ');

          $mysql  = new MySQLConn(parent::getHost(), parent::getDB(), parent::getUser(), parent::getPass());
          $jtm    = new JSONtoMYSQL($mysql);

          Registry::set('JSONtoMYSQL', $jtm);
          Registry::lock('JSONtoMYSQL');

        }
        return $result;
    }

     public static function TableExists($table) {
      if (self::$conn->query("SHOW TABLES LIKE '$table'") === FALSE){

        self::$context->setState(self::$context->getStoptState()); 
        self::$context->doState(); exit();
        }
      }

    public static function getConnection() { return self::$conn; }

    public static function getRoute($keyRoute){
      self::TableExists('route');
      $query = "SELECT valRoute FROM route WHERE route.keyRoute = ? LIMIT 1";
      if ($stmt = self::$conn->prepare($query)) {
         $stmt->bind_param("s", $keyRoute);
          $stmt->execute();
          $stmt->bind_result($valRoute);
           $stmt->fetch();
           $stmt->close();
           return $valRoute;
       }   else{  Route::ErrorPage404();}
    }

    public static function getAliasAddress($alias){
     $method = $_SERVER['REQUEST_METHOD'];
     self::TableExists('site_alias');
     if($method==='GET'){
       $result=false;
       $query = "SELECT   site_alias_address FROM site_alias WHERE site_alias.site_alias = ? LIMIT 1";
       if ($stmt = self::$conn->prepare($query)) {
        $stmt->bind_param("s", $alias);
        $stmt->execute();
        $stmt->bind_result($site_alias_address);
        $stmt->fetch();
        $result=$site_alias_address;
        $stmt->close();
      }
      if($result===false||$result==''){
        $result=$alias;
      }
      return $result;
    }else{
      return  $alias;
    }
  }

}

?>