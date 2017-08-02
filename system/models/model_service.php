<?php
	class Model_Service extends Model
	{
		function __construct()
		{
		parent::__construct();
		    include ('./system/libs/adms.lib.php');
		    include ('./system/libs/cron.lib.php');
		    //Example

			$crontab=new crontab("./system/cron/", "filename", 'w'); 
			$crontab->setDBObject(parent::$db); 
			$crontab->title = 'test'; 
			$crontab->setDateParams(2, 1, 1, 1, "*"); 
			$crontab->setCommand("wget http://somedomain.com/index.php?mod=sendcron"); 
			$crontab->savetodb(); 

			/* 
			If you want to add more croncommand use: 
			$crontab->clearParameters(); 
			$crontab->setDateParams(5, 10, 5, 5, "*"); 
			$crontab->setCommand("curl http://somedomain.com/index.php?mod=sendcron"); 
			$crontab->savetodb(); 
			*/ 

			$crontab->saveCronFilebydb(); 
			//$crontab->addToCrontab(); 

		    // END Example
		}

		public function doAction($jinit , $jdata){

			

			  $ainit=json_decode($jinit,true);
		      $adata=json_decode($jdata,true);

		      //var_dump($ainit);

		  	  $PHPFILE = new PHPFILE(parent::$db);
		        $PHPFILE->RunFile();
		        $PHPFILE->doAction($jinit,$jdata);
		        $PHPFILE->CloseFile();
		}
		   function rest_post(){
		     $json = file_get_contents('php://input');
		     $jsondata=json_decode($json, true);
		     $arrValInit = array('who' => $jsondata["who"], 'what' => $jsondata["what"]);
		     $valInit    = json_encode($arrValInit);
		     unset($jsondata['who']);  unset($jsondata['what']);
		     $valData    = json_encode($jsondata);
		     return  $this->doAction($valInit , $valData);
		  }
		  function rest_get(){
		     $allArr     = $_GET;
		     $arrValInit = array('who' => $allArr["who"], 'what' => $allArr["what"]);
		     $valInit    = json_encode($arrValInit);
		     unset($allArr['who']);  unset($allArr['what']); unset($allArr['do']);
		     $valData    = json_encode($allArr);
		     //self::$admService->doAction($valInit , $valData);
		     return  $this->doAction($valInit , $valData);
		  }
       }
?>