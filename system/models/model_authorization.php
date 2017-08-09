<?php
	class Model_Authorization extends Model
	{
		function __construct()
		{
		  parent::__construct();
		}
		 public function doAction($jinit , $jdata){

		  	    $ainit=json_decode($jinit,true);
    	        $adata=json_decode($jdata,true);

			  	if(method_exists($this, $ainit['what']))
		        {
		        	parent::$value=$ainit['value'];
		            return $this->$ainit['what']($adata);
		        }
		        else{
		        	Controller::returnMessage("СБОЙ ПРОГРАММЫ", "Danger" ,"OPS");
			        exit;
		        }
		  }
		  function auth($adata){
		  	$is_error=true;
		  	$u=$adata['user'];
		  	$query = "SELECT login_user , passwd_user, real_name, user_id FROM auth_users WHERE  login_user = ?  AND  passwd_user = ? LIMIT 1;";
			if ($stmt = parent::$db->prepare($query)) {
				$stmt->bind_param("ss", $u['login'],$u['pass']);
			    $stmt->execute();
$stmt->store_result();
			    $stmt->bind_result($login, $passwd, $rname, $uid);
			    while ($stmt->fetch()) {
			    	Session::set('UID', $uid);
			    	 $myACL = new ACL();
			    	 Registry::set('USEROBJ' ,  $myACL);
                      Registry::lock('USEROBJ');
                    Session::set('USER',$rname);
                    $is_error=false;
			    }
			    $stmt->close();
			}
			if($is_error){Controller::returnMessage("Пользователь или пароль не верны !", "Warning" ,"Access dae !!!");}
			else{
			        //~ ставим куки на 1 день
			        setcookie("UID", $uid, time()+3600*24);
			        setcookie("RCOD", $r_code, time()+3600*24);
			        Session::set('loggedIn', true);
			        return true;
			    }
		  }
	}
?>