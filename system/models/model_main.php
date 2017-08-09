<?php
  class Model_Main extends Model
  {
      protected static $sitexscms   =null;

      function __construct()
      {
        parent::__construct();
        self::$sitexscms=new SiteXSCMS();
        Registry::set('xsrf', '<script>angular.module("XSCMS_SITE").constant("CSRF_TOKEN", "'.$_SESSION['XSRF'].'");</script>'); 
      }

      public function doAction($jinit , $jdata){

            $ainit  =json_decode($jinit,true);
            $adata=json_decode($jdata,true);

          if(method_exists($this, $ainit['what']))
            {
              parent::$value=$ainit['value'];
                return $this->$ainit['what']();
            }
            else{
              return "error";
            }
      }

       function getpage()
      {
           return self::$sitexscms->getpage('home');
      }

      function getconfig()
      {
           return self::$sitexscms->getconfig(parent::$value);
      }

    }
?>