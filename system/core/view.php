<?php
include ('system/libs/template_engine/TXSCMS.php');

class View
{
    private static $nodeCSS  = array();
    private static $nodeJS   = array();
    private static $nodeMETA = array();


    function generate($content_view, $template_view,$logs=null)
    {
       // global $Debug;
//        if($logs!=null){$vars_debug = array('logs' =>$logs);}
        XSCMS::registerAutoload();
        include 'system/views/'.$template_view;
    }

    function setCSS($css_path){
    $host     = 'http://'.$_SERVER['HTTP_HOST'].'/';
    $css_path=str_replace("../","",$css_path);

    array_push(self::$nodeCSS, '<link href="'.$host.$css_path.'" rel="stylesheet" type="text/css" />');
    Registry::set('nodeCSS'      , self::$nodeCSS);
    }

    function setJS($js_path,$is_script=false){
    if(!$is_script) {   
        $host     = 'http://'.$_SERVER['HTTP_HOST'].'/';
        $js_path  = str_replace("../","",$js_path);
        array_push(self::$nodeJS, '<script src="'.$host.$js_path.'" type="text/javascript"></script>');
    }else{array_push(self::$nodeJS, '<script>'.$js_path.'</script>');}
    
    Registry::set('nodeJS'       , self::$nodeJS);
    }

    function setMeta($content){
    array_push(self::$nodeMETA, $content);
    Registry::set('nodeMETA'     , self::$nodeMETA);
    }

}
