<?php

/*
Класс-маршрутизатор для определения запрашиваемой страницы.
> цепляет классы контроллеров и моделей;
> создает экземпляры контролеров страниц и вызывает действия этих контроллеров.
*/
class Route
{

    static function start(XSContext $context)
    {
        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';
        $address='';
        //
         $myACL = new ACL();
        Registry::set('USEROBJ' ,  $myACL);
      //  var_dump($_SERVER['REQUEST_URI']);
     //   exit;
        $aliasAdr=$context::getAliasAddress($_SERVER['REQUEST_URI']);

        // редиректор
        if(strlen($aliasAdr)>1){
            $pos = stripos($aliasAdr, '?');
            if ($pos == false) {
                $rest = substr($aliasAdr, -1);
                if($rest !=='/')
                {Route::Redirect($aliasAdr); exit;}
            }
        }

        $routes       = explode('/', $aliasAdr);
        $action       = explode('/?', $aliasAdr);



        if ( !empty($action[1]) )
        {
            $strpos=($strpos=mb_strpos($action[1],'&'))!=false?mb_substr($action[1],0,$strpos,'utf8'):$action[1];
            $params_b= explode("&",str_replace("/","",str_replace($strpos, "",   $action[1])));
            unset($params_b[0]);
            $action[1]=$strpos;
            $params= array( $action[1]=>'');
            foreach ($params_b as  $element) {
               $z=explode("=",$element);
               if(array_key_exists(1, $z)){
                $params[$z[0]]=$z[1];
               }else{
                 $params[$z[0]]="";
               }
            }
              $_GET=$params;
        }


        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            if($routes[1]!='logout'){
               if(mb_substr($routes[1],0,1,"UTF-8")=='?'){
                  $controller_name = 'Main';
               } else{$controller_name =  $context::getRoute($routes[1]);       }
            }
            else{ Route::Logout(); exit;}
        }
        if(empty($controller_name) ){
             $controller_name="404";
        }
        // получаем имя экшена
        if ( !empty($action[1]) )
        {
            $action_name = $action[1];
        }

        // добавляем префиксы
        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;


        //$GLOBALS["Debug"]->AddMessage('Controller_name', $controller_name);

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = strtolower($model_name).'.php';
        $model_path = "system/models/".$model_file;
        if(file_exists($model_path))
        {
            include "system/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "system/controllers/".$controller_file;

        if(file_exists($controller_path))
        {
            if(!Registry::get('USEROBJ')->hasPermission($controller_name,'controller')){
                Route::ErrorPage404();
                exit();
            }

            include $controller_path;
        }
        else
        {
           //  $GLOBALS["Debug"]->AddMessage('$controller_name', $controller_name);
            Route::ErrorPage404();
            exit;
        }

        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {

            $back = '';
            Registry::set('PreviewPage',  $back);
            if(!Registry::get('USEROBJ')->hasPermission($action_name,$controller_name)){
                   Route::ErrorPage404();
                    exit();
             }
            // вызываем действие контроллера
            $controller->$action();
        }
        else
        {
             //$GLOBALS["Debug"]->AddMessage('$action', $action);

            exit;
        }

    }

    static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.0 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404/');
    }

    static function Authorization()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Location:'.$host.'authorization/');
    }

    static function Logout()
    {
       $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Location:'.$host.'authorization/?logout');
    }

    static function Redirect($node)
    {
       $node=substr($node, 1);
       $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Location:'.$host.$node.'/');
    }

}
