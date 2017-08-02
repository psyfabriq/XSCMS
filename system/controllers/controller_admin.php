<?php
class Controller_Admin extends Controller
{
  function __construct()
  {
     parent::__construct();
  }
function action_index()
{
    parent::action_index();

    $arrayCSS_Alias=$this->model_general->getListCSS('admin');
    $arrayJS_Alias   =$this->model_general->getListJS('admin');

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
  $jsKeys=$this->model->getJS();
  foreach ($jsKeys  as $js_key) {
     $this->view->setJS('res/?js&'.$js_key);
  }
 $this->view->setJS('angular.module("XSCMS_ADMIN_DASHBOARD").constant("CSRF_TOKEN", "'.Session::get('XSRF').'");',true);
Registry::set('html_parametrs', ' ng-app="XSCMS_ADMIN_DASHBOARD"');
		// выполнение работы сборшика
$this->view->generate('admin_view.php', 'template_view.php',true);
}

function action_do()
{
 parent::action_do();
}



}