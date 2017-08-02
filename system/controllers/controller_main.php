<?php

class Controller_Main extends Controller
{
	function __construct()
	{
	   parent::__construct();
	}

	function action_index()
	{

		//var_dump('expression');
		parent::action_index();
		//$GLOBALS["Debug"]->CleanLogs();
		$arrayCSS_Alias=$this->model_general->getListCSS('site');
                 $arrayJS_Alias   =$this->model_general->getListJS('site');

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
         $this->view->setJS('angular.module("XSCMS_SITE").constant("CSRF_TOKEN", "'.Session::get('XSRF').'");',true);
		Registry::set('html_parametrs', ' ng-app="XSCMS_SITE"');
		$this->view->generate('content_view.php', 'template_view.php');
	}
}