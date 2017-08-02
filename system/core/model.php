<?php
//include 'compress_hashkeys.php';
//require_once('Compressor/compressor.php');
 class Model
{
	private static $comp =null;
	public static $db=null;
	public static $mdb=null;
	public static $value=null;

	public function __construct()
	{
		self::$db  = Registry::get('DBOBJ');
		/*
		$objm = Registry::get('MONDBOBJ');
		if($objm['status']){
			self::$mdb = $objm['obj'];
		}
		*/
	}

	function getKey() {
	    $class = __CLASS__;
	    $t = get_class($this);
	    while (($p = get_parent_class($t)) !== false) {
	        if ($p == $class) {
	           return str_replace('Model_', '',$t);
	        }
	        $t = $p;
	    }
	    return false;
	}

      function rest_res(){
      	$allArr     = array_keys($_GET);
      	$nf="get".$allArr[0];
      	self::$value=str_replace("/", "", $allArr[1]);
      	return  $this->$nf();
      }


	 function rest_post(){

	 	   $headerToken = $_SERVER['HTTP_PFQ_TOKEN'];
	 	   // echo $headerToken;
	 	   // echo "  |||||  ";
		   $sessionToken = $_SESSION['XSRF'];
		  // echo $sessionToken;
		   if($headerToken!=$sessionToken){
		     header('HTTP/1.0 401 Unauthorized');
		      exit;
		   }

	     $json = file_get_contents('php://input');
	     $jsondata=json_decode($json, true);
	     //costil
	     if(empty($jsondata["type"])){$jsondata["type"]="";}

	     $arrValInit = array('value' => $jsondata["type"], 'what' => $jsondata["what"]);
	     $valInit    = json_encode($arrValInit);
	     unset($jsondata['type']);  unset($jsondata['what']);
	     $valData    = json_encode($jsondata);
	     return  $this->doAction($valInit , $valData);
	  }
	 function rest_get(){

         $headerToken = $_SERVER['HTTP_PFQ_TOKEN'];
	 	   // echo $headerToken;
	 	  //  echo "  |||||  ";
		   $sessionToken = $_SESSION['XSRF'];
		  // echo $sessionToken;
		   if($headerToken!=$sessionToken){
		     header('HTTP/1.0 401 Unauthorized');
		      exit;
		   }


	     $allArr     = $_GET;
	      //costil
	     if(empty($allArr["type"])){$allArr["type"]="";}

	     $arrValInit = array('value' => $allArr["type"], 'what' => $allArr["what"]);
	     $valInit    = json_encode($arrValInit);
	     unset($allArr['type']);  unset($allArr['what']);
	     $valData    = json_encode($arrValInit);
	     return  $this->doAction($valInit , $valData);
	  }
	 function rest_put()    {}
	 function rest_head()   {}
	 function rest_delete() {}
	 function rest_options(){}

	 public static function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
      }

}