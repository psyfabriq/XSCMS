<?php

class Controller {

  public  $model;
  public  $view;
  public static $Global;
  public $model_general;

  const Danger  = 'Danger';
  const Info    = 'Info';
  const Warning = 'Warning';
  const Success = 'Success'; 


  function __construct()
  {
    
    $this->loadModel();
    if (class_exists('Model_General')) {
     $this->model_general = new Model_General();
   }
 }

 function loadModel(){

   $model_name = str_replace('Controller', 'Model',self::get_child($this, __CLASS__));
   $GLOBALS["Debug"]->AddMessage('Modal Name', $model_name);
   if (class_exists($model_name)) {
    $this->model = new $model_name();
    $GLOBALS["Debug"]->AddMessage('Modal Exist', 'true');
  }
}
	// действие (action), вызываемое по умолчанию
function action_index(){
    $token = hash('sha256', uniqid(mt_rand(), true));
    Session::set('XSRF',$token);
    $this->view  = new View();

    $this->view->setMeta('<meta http-equiv="content-type" content="text/html; charset=utf-8" />');
    $this->view->setMeta('<meta name="fragment" content="!" />');

    //$arrayCSS_Alias = explode("\n", file_get_contents(str_replace('../', '', Registry::get('Resource')).'css.core.list'));
   // $arrayJS_Alias    = explode("\n", file_get_contents(str_replace('../', '', Registry::get('Resource'))."js.core.list"));

    $arrayCSS_Alias=$this->model_general->getListCSS('core');
    $arrayJS_Alias   =$this->model_general->getListJS('core');

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
    
  }

  function action_logout()
  {
   $back = $_SERVER['HTTP_REFERER'];
   Registry::set('PreviewPage',  $back);
   Session::destroy();
   exit;
 }

 function get_child($instance, $classname) {
   $class = $classname;
   $t = get_class($instance);
   while (($p = get_parent_class($t)) !== false) {
     if ($p == $class) {
       return $t;
     }
     $t = $p;
   }
   return false;
 }

 public static function returnMessage($message, $type, $header){
   header('Content-Type: application/json');
   if($type==self::Danger || $type==self::Warning){
    $data_type="message_error";
  }else{	$data_type="message";}
  global $Debug;
  $Debug->End();
  echo json_encode(array ('data_type'=>$data_type ,'message'=>$message,'mtype'=>$type,'header'=>$header));
}
public static function returnData($data){
 global $Debug;
 $Debug->End();
 header('Content-Type: application/json');
 echo $data;
}

function action_do(){

  if (is_object($this->model)) {
   // header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
      case 'PUT':
      echo $this->model->rest_put();
      break;
      case 'POST':
      echo $this->model->rest_post();
      break;
      case 'GET':
      echo $this->model->rest_get();
      break;
      case 'HEAD':
      echo $this->model->rest_head();
      break;
      case 'DELETE':
      echo $this->model->rest_delete();
      break;
      case 'OPTIONS':
      echo $this->model->rest_options();
      break;
      default:
      self::returnMessage("NONE REQUEST_METHOD => ".$method ,Danger,"ACTION ERROR");
      break;
    }
  }
}
}
