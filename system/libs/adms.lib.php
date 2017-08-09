<?php
defined('_XSCMS_PFQ_ADM_LIB') or die('ERROR');

class PHPFILE
{
	private static $fileName='';
	private static $dirName='';

	protected static $db=null;
	protected static $uo   =null;

	public function __construct($db){

		self::$db=$db;
		self::$uo = Registry::get('USEROBJ');
		if (!self::$db){
    		 Controller::returnMessage("Can't connect to MySQL: " .mysqli_connect_error(), "Danger", "OPS");
    		 exit;
    	}
	}
	private function generateName($length) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ";
      $code = "";
      $clen = strlen($chars) - 1;
      while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
      }
      return $code.'.xsphp';
    }
    private function getDir(){
    	 $dir = 'injection';
    	 if ( !file_exists($dir) ) {
		  mkdir ($dir, 0744);
		 }
		 return $dir;
    }

    private function CheckIfNewCode($who){
    	$result=true;

        $query = "SELECT new_container as N FROM php_container where who_container='".$who."'";
    	if ($r = self::$db->query($query)) {
    		if($r->num_rows < 1){
    		  Controller::returnMessage("Not found ".$who." code !!! ", "Danger" ,"OPS");
    		  exit;
    	    }
	   	    while ($row = $r->fetch_assoc()) {
	   	    	$val=$row["N"];
	   	    	if($val==1){
	   	    		$result=true;
	   	    	}
	   	    	else{
	   	    		$result=false;
	   	    	}
	   	    	break;
		    }
		}
		else{
			Controller::returnMessage("Can't get ".$who." code !!! ", "Danger" ,"OPS");
			exit;
		}
		return $result;
    }
    private function CheckCode($who){
    	 $query= "UPDATE php_container SET new_container='0' where who_container='".$who."'";
         if (!self::$db->query($query)) {
              Controller::returnMessage(self::$db->error, "Danger" ,"OPS");
              exit;
          }
    }
    private function GetCodePhp($who){
    	$val='';

    	 $query = "SELECT code_container as C FROM php_container where who_container='".$who."'";
	    	if ($r = self::$db->query($query)) {
		   	    while ($row = $r->fetch_assoc()) {
		   	    	$val=$row["C"];
		   	    	break;
			    }
			}
			else{
				Controller::returnMessage("Can't get ".$who." code !!! ", "Danger" ,"OPS");
				exit;
			}

    	return base64_decode($val);
    }

    public function RunFile() {
    	self::$dirName=self::getDir();
    	//self::$fileName=self::generateName(20);
    	$generalFile=self::$dirName.'/generalDo.xsphp';

    	//Run or generate general controler php

    	$createFile=self::CheckIfNewCode('AdminApp_General');

    	if($createFile || !file_exists($generalFile)){
    		$handle_g = fopen($generalFile,'w') or die('Cannot open file:  generalDo.xsphp');

    		$cod=self::GetCodePhp('AdminApp_General');

			if (fwrite($handle_g, $cod) === FALSE) {
				fclose($handle_g);
				Controller::returnMessage("Cannot write to file (generalDo.xsphp)", 'Danger', 'OPS');
			    exit;
			}
			else{
				self::CheckCode('AdminApp_General');
			}
			fclose($handle_g);
			file_put_contents($generalFile, "?>", FILE_APPEND);
    	}
    	include $generalFile;
    }
    public function CloseFile() {
    }
    public function doAction($jinit,$jdata) {

    	   $ainit=json_decode($jinit,true);
    	   $adata=json_decode($jdata,true);

    	   $fileName='doController_'.$ainit['who'].'.xsphp';

    	   $doControllerFile=self::$dirName.'/'.$fileName;

    	   $createFile=self::CheckIfNewCode($ainit['who']);

    	   if($createFile || !file_exists($doControllerFile)){
	    		$handle_d = fopen($doControllerFile,'w') or die('Cannot open file:  '.$fileName);

	    		$cod=self::GetCodePhp($ainit['who']);

				if (fwrite($handle_d, $cod) == FALSE) {
					fclose($handle_d);
					Controller::returnMessage("Cannot write to file ".$fileName, 'Danger', 'OPS');
				    exit;
				}
				else{
					self::CheckCode($ainit['who']);
				}
				fclose($handle_d);
				file_put_contents($doControllerFile, "?>", FILE_APPEND);
	    	}

	    	include $doControllerFile;

	    	$action_name     = 'doAction_'.$ainit['what'];
            $controller_name = 'DoController_'.$ainit['who'];

            // создаем контроллер
            if (class_exists($controller_name)) {
                        if(!Registry::get('USEROBJ')->hasPermission($controller_name,'do_controller')){
                            Controller::returnMessage("Access die !!!", 'Danger', 'OPS');
                            exit();
                        }
			    $controller = new $controller_name;
			}
	        else{
	        	Controller::returnMessage("Class not found -> ".$controller_name, 'Danger', 'OPS');
	            exit;
	        }
	        $action = $action_name;

	        if(method_exists($controller, $action))
	        {
                  if(!Registry::get('USEROBJ')->hasPermission($action_name,$controller_name)){
                            Controller::returnMessage("Access die !!!", 'Danger', 'OPS');
                            exit();
                        }
	            $controller->$action($adata);
	        }
	        else
	        {
	            Controller::returnMessage("Not found Action -> ".$action." in doController -> ".$controller_name, 'Danger', 'OPS');
	            exit;
	        }
    }

}


