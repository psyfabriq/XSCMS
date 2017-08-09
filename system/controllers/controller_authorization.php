<?php
 
class Controller_Authorization extends Controller
{

	function action_index()
	{	
		 
		 $logged = Session::get('loggedIn');
         if($logged == false) {
         	 parent::action_index();
         	// $this->view->setJS(Registry::get('ResourceSite').'js/script.js');

         	    $arrayCSS_Alias=$this->model_general->getListCSS('auth');
			    $arrayJS_Alias   =$this->model_general->getListJS('auth');

			    foreach ($arrayCSS_Alias  as $alias) {
			        if(!empty($alias)){
			          $css_key=$this->model_general->getcss($alias);
			          $this->view->setCSS('res/?css&'.$css_key);
			      }
			  }

			  foreach ($arrayJS_Alias  as $alias) {
			    if(!empty($alias)){
			      $js_key=$this->model_general->getjs($alias);
			      $this->view->setJS('res/?js&'.$js_key);
			  }
			}
			 $this->view->setJS('angular.module("XSCMS_AUTHORIZATION").constant("CSRF_TOKEN", "'.Session::get('XSRF').'");',true);
         	 Registry::set('html_parametrs', ' ng-app="XSCMS_AUTHORIZATION"');
		     $this->view->generate('authorization_view.php', 'template_view.php');
	     }
	     else{
		    header('Location: /');
			exit();	
	     }
	}

	function action_process()
	{
		if(Session::authorization())
		{header('Location: /');}
		else{self::returnMessage("Ueser or login not corrected !!",Danger,"ACTION ERROR");}
		exit;	
	}
}