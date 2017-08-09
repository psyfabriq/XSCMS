<?php
define('_XSCMS_PFQ_ADM_LIB', 1);
class Controller_Resource extends Controller
{
      public function __construct(){
        $this->loadModel();
      }

      function action_css()
      {
          if (is_object($this->model)) {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method==='GET'){
               header("Content-type: text/css; charset: UTF-8");
               echo $this->model->rest_res();
            }
          }
      }

      function action_js()
      {
          if (is_object($this->model)) {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method==='GET'){
               header("Content-Type: application/javascript; charset: UTF-8");
               echo $this->model->rest_res();
            }
          }
      }

      function action_fonts(){
       if (is_object($this->model)) {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method==='GET'){
               $objFonts=$this->model->rest_res();

               if($objFonts['result']===true){
                              header("Content-Type: ".$objFonts['header']);
                              header("Content-Disposition: attachment;");
                              echo $objFonts['data'];
               }
            }
          }
    }

    function action_index(){ Route::ErrorPage404(); exit(); }
}
?>