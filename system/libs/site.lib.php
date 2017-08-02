<?php
class SiteXSCMS
{
   protected static $db   =null; 
   protected static $uo   =null;
   function __construct() { 
                            self::$db = Registry::get('DBOBJ');
                            self::$uo = Registry::get('USEROBJ');
                          }

   function getpage($key)
      {
          if ($stmt = self::$db->prepare("SELECT data FROM page WHERE data_key=?")) {
            $stmt->bind_param("s", $key);
            $stmt->execute();
$stmt->store_result();
            $stmt->bind_result($district);
            $stmt->fetch();
            $stmt->close();
             if(empty($district)){$district="<h1> ERROR ! </h1>";}
            return $district;
         }
      }

      function getIn($valarr){
          $val='';
          foreach($valarr as $key => $value) {
             $val.="'".$value."',";
          }
          $val=rtrim($val,',');
          return $val;
      }

     function getblocks(){
      $arr = array();
      $q_poligons="SELECT
                    site_template_engine_poligons.po_mashine_name
                  FROM site_template_engine
                    RIGHT OUTER JOIN site_template_engine_poligons
                      ON site_template_engine.te_key = site_template_engine_poligons.te_key_parent
                  WHERE site_template_engine.te_type = 'site'";
        if ($stmt = self::$db->prepare($q_poligons)) {
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($poligon);
                while($stmt->fetch()){
                  $arr[$poligon]= array();
                }
                $stmt->close();
        }

        foreach ($arr as $key => $value) {
          $arrBlocks=array();
          $arrKeys  = array();
          $q_bloks="SELECT
                          site_blocks.title,
                          site_blocks.title_show,
                          site_blocks.title_auto,
                          site_blocks.block_key,
                          site_blocks.code,
                          site_blocks.type
                        FROM site_template_engine
                          RIGHT OUTER JOIN site_blocks
                            ON site_template_engine.te_key = site_blocks.theme
                        WHERE site_blocks.place = ?
                        AND site_template_engine.te_type = 'site'
                        AND site_blocks.active = 1
                        ORDER BY site_blocks.weight";

          if ($stmt = self::$db->prepare($q_bloks)) {
                $stmt->bind_param( "s" ,$key);
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($title,$title_show,$title_auto,$block_key,$code,$b_type);
                while($stmt->fetch()){
                  $arrBlocks[$block_key] = array( 'title' =>$title,
                                                                'show_tilte'=>$title_show,
                                                                'template'=>$code,
                                                                'b_type'=>$b_type);
                  array_push($arrKeys, $block_key);
                }
                $stmt->close();
               $arr[$key] =$arrBlocks;
          }

           $inValue=self::getIn($arrKeys);
           $q_bloks_additionals="SELECT
                                              site_blocks_additional.additional_key,
                                              site_blocks_additional.additional_value,
                                              site_blocks_additional.block_key
                                            FROM site_blocks_additional
                                            WHERE site_blocks_additional.block_key IN ($inValue)";


           if ($stmt = self::$db->prepare($q_bloks_additionals)) {
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($additional_key,$additional_value,$block_key);
                while($stmt->fetch()){
                  $arr[$key][$block_key][$additional_key]=$additional_value;
                }
                $stmt->close();
                foreach ($arr[$key] as $block_key => $value) {
                  if($value['b_type']=='menu_mod'){
                     $menu=$value['menu']; unset($arr[$key][$block_key]['menu']);
                     $arr[$key][$block_key]['value']=$this->getmenu($menu);
                  }
                  elseif ($value['b_type']=='view_mod') {
                     $ctype=$value['ctype']; unset($arr[$key][$block_key]['ctype']);
                     $cview=$value['cview']; unset($arr[$key][$block_key]['cview']);

                  }
                  elseif ($value['b_type']=='custom_mod') {}
                  unset($arr[$key][$block_key]['b_type']);
                }
          }

        }

      $res=json_encode($arr);
      return  $res;
     }

      function getmenu($option)
      {
        $ur=self::$uo->getUserRoles();
        $ids = join(',', $ur); 



             $GLOBALS["Debug"]->AddMessage('l'.__LINE__, get_class($this).' on line '.__LINE__ );
             $GLOBALS["Debug"]->AddMessage('UsersRoles', $ids);
             $GLOBALS["Debug"]->End(true);

        $rootArray =array();
        $childArray=null;
        $menu_title_old='';

        $query = "SELECT
                        sitet.title AS menu_title,
                        sitet.data_key AS data_key,
                        sitet.weight AS weight
                      FROM site_typmenu sitet
                        LEFT OUTER JOIN menu_access
                          ON sitet.data_key = menu_access.idi 
                      WHERE sitet.data_key = ? AND menu_access.idr IN ($ids)
                      GROUP BY sitet.data_key
                     
                      UNION
                     
                      SELECT
                        admt.title AS menu_title,
                        admt.data_key AS data_key,
                        admt.weight AS weight
                      FROM adm_typmenu admt
                        LEFT OUTER JOIN menu_access
                          ON admt.data_key = menu_access.idi
                      WHERE admt.data_key = ? AND menu_access.idr IN ($ids)
                      GROUP BY admt.data_key
                      ORDER BY weight
                      ";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("ss", $option, $option);
          $stmt->execute();
$stmt->store_result();
          $stmt->bind_result($menu_title,$data_key,$weight);
          while ($stmt->fetch()) {
              if($childArray==null){
                $childArray=array("MenuTitle" => $menu_title,"data_key"=>$data_key,"links" => array());
              }
              $menu_title_old=$menu_title;
              array_push($rootArray, $childArray);
          }
          $stmt->close();

          foreach ($rootArray  as $key => $categoryChild) {
                 $rootArray[$key]['links'] = $this->getCategory($rootArray[$key]['data_key'],0);
                    if(!empty( $rootArray[$key]['links'])){
                         $rootArray[$key]['sub']=true;
                           }else{
                               $rootArray[$key]['sub']=false;
                           }
           }
      }

      return $rootArray;
      }

      function getCategory($data_key,$parent_id){
             $ur=self::$uo->getUserRoles();
             $ids = join(',', $ur); 
            $arrayRoot= array();
   
            /*

             UNION

                      SELECT
                        am.title AS title,
                        am.url AS url,
                        am.weight AS weight,
                        am.id AS id,
                        am.classm AS classm,
                        am.stylem AS stylem
                      FROM adm_menu am
                        RIGHT OUTER JOIN menu_access
                          ON am.data_key = menu_access.data_key AND am.item_key = menu_access.idi
                      WHERE am.data_key = ? AND am.parent_id = ? AND menu_access.idr IN (ids)

            */

             $query = "SELECT
                        sm.title AS title,
                        sm.url AS url,
                        sm.weight AS weight,
                        sm.id AS id,
                        sm.classm AS classm,
                        sm.stylem AS stylem
                      FROM site_menu sm
                        RIGHT OUTER JOIN menu_access
                          ON sm.item_key = menu_access.idi AND sm.data_key = menu_access.data_key
                      WHERE sm.data_key = ? AND sm.parent_id = ? AND menu_access.idr IN ($ids)
                      GROUP BY sm.item_key     
                      ORDER BY weight";
 

                  if ($stmt = self::$db->prepare($query)) {
                      $stmt->bind_param("si", $data_key,$parent_id);
                      $stmt->execute();
$stmt->store_result();
                      $stmt->bind_result($titlen, $url, $weight, $id, $classm, $stylem);
                      while ($stmt->fetch()) {
                          $arrayItemn = array('name'=> $titlen,
                                              'url'=> '#!'.$url,
                                              'id'=> $id,
                                              'classm'=>$classm,
                                              'stylem'=>$stylem,
                                              'links'=> []);

                          array_push($arrayRoot, $arrayItemn);

                      }
                      $stmt->close();

                      if (!empty($arrayRoot)){
                           foreach ($arrayRoot  as $key => $category) {
                               $arrayRoot[$key]['links']=$this->getCategory($data_key,$arrayRoot[$key]['id']);
                               unset($arrayRoot[$key]['id']);
                               if(!empty( $arrayRoot[$key]['links'])){
                                   $arrayRoot[$key]['sub']=true;
                               }else{
                                   $arrayRoot[$key]['sub']=false;
                               }
                           }
                      }
                  }
             return $arrayRoot;
       }

     function getmainpage(){
      $arr=array();
       $q_main_page="SELECT
                                      page.data
                                    FROM page
                                    WHERE page.data_key = 'main_page'";
     }
     function getmainviews(&$arrVKey,&$arrNodes){
      $arrayRoot = array();
      $q="SELECT
              site_contenttype_view.node_title AS title,
              site_contenttype_view.code AS template,
              site_contenttype_view.`key` AS vkey,
              site_contenttype_view.show_node_title AS shownodetitle,
              site_contenttype_view.node_title_automatic AS nodetitleautomatic,
              site_contenttype_view.mashine_name AS content_type,
              site_content_main_views_weight.wseight AS wseight
            FROM site_content_main_views_weight
              LEFT OUTER JOIN site_contenttype_view
                ON site_content_main_views_weight.view_ikey = site_contenttype_view.`key`
                AND site_content_main_views_weight.`show` = 1
            ORDER BY site_content_main_views_weight.wseight";

            if ($stmt = self::$db->prepare($q)) {
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($c_title,$c_template,$c_vkey,$c_show_node_title, $c_node_title_automatic,$c_content_type,$c_weight);
                while ($stmt->fetch()) {
                  $arrayItem = array('content_type' => $c_content_type, 
                                            'title' => $c_title, 
                                         'template' => $c_template,
                                              'vkey'=>$c_vkey, 
                                         'showtitle'=>$c_show_node_title, 
                                    'automatictitle'=>$c_node_title_automatic,
                                    'weight'=>$c_weight);
                        $arrayRoot[$c_vkey]= $arrayItem;
                       // array_push($arrayRoot, $arrayItem);
                        array_push($arrVKey, $c_vkey);
                }
                $stmt->close();
              }
              foreach ($arrayRoot as $key => $value) {
                $arrayRoot[$key]['content']=self::getmaincontents($key);
                //$arrNodes = array_merge($arrNodes, array_keys($arrayRoot[$key]['content']));
              }

              return $arrayRoot ;
     }

     function getmaincontents($vkey){
       $arrayRoot = array();
       $arrayRoot_tmp = array();
       $q="SELECT
            site_content.content_title,
            site_content.content_date,
            site_content.content_author,
            site_content.content_tags,
            site_content.content_address,
            site_content.content_node,
            site_content_options_value_default.value AS weight
          FROM site_content
            INNER JOIN site_content_main_views
              ON site_content.content_node = site_content_main_views.node AND site_content_main_views.view_ikey = ?
            INNER JOIN site_content_options_value_default
              ON site_content.content_node = site_content_options_value_default.node AND site_content_options_value_default.option_mashine_name = 'c_weight'";

         if ($stmt = self::$db->prepare($q)) {
                $stmt->bind_param( "s" ,$vkey);
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($title,$data,$author,$tags, $address,$node,$weight);
                while ($stmt->fetch()) {
                  $weight = $weight !=null ? intval(json_decode(base64_decode($weight))->value) : 0;

                  $arrayItem = array('title' => $title, 'data' => $data, 'author' => $author,'tags'=>$tags,'address'=>$address ,'weight'=>$weight);
                        $arrayRoot_tmp[$node]= $arrayItem;
                         // array_push($arrayRoot, $arrayItem);
                }
                $stmt->close();
                foreach ($arrayRoot_tmp as $key => $value) {
                   $arrayRoot_tmp[$key]['fields']=self::getfields($vkey,"",$key);
                   array_push($arrayRoot, $arrayRoot_tmp[$key]);
                }
              }

       return $arrayRoot;
     }

     function getconfig($value)
      {
          header('Content-Type: application/json');
          $res='[{"content_type" : "404", "title" : "Not Found" ,"template": "PGRpdiBjbGFzcz0iZXJyb3I0MDQiPjwvZGl2Pg=="}]';
          $q="SELECT
                  site_contenttype.contenttype_mashine_name AS content_type,
                  site_contenttype_view.node_title  AS title,
                  site_contenttype_view.code AS template,
                  site_contenttype_view.`key` AS vkey,
                  site_contenttype_view.show_node_title AS shownodetitle,
                  site_contenttype_view.node_title_automatic AS nodetitleautomatic
                FROM site_contenttype
                  LEFT OUTER JOIN site_contenttype_view
                    ON site_contenttype.contenttype_mashine_name = site_contenttype_view.mashine_name
                WHERE site_contenttype.contenttype_address = ?  AND site_contenttype_view.is_this_block = FALSE ";

           $q_single="SELECT
                            site_contenttype.contenttype_mashine_name AS content_type,
                            site_contenttype_view.node_title AS title,
                            site_contenttype_view.code AS template,
                            site_contenttype_view.`key` AS vkey,
                            site_contenttype_view.show_node_title AS shownodetitle,
                            site_contenttype_view.node_title_automatic AS nodetitleautomatic
                          FROM site_contenttype
                            INNER JOIN site_contenttype_view
                              ON site_contenttype.contenttype_mashine_name = site_contenttype_view.mashine_name
                            INNER JOIN site_content
                              ON site_contenttype_view.`key` = site_content.content_single_view
                          WHERE site_contenttype_view.is_this_single = 1
                          AND site_content.content_is_single = 1
                          AND site_content.content_type = site_contenttype.contenttype_mashine_name";

          if(empty($value)){
            $arr = array(array('content_type' =>"HOME", 'title' =>"Home",'template'=> "PGgxPlVuZGVyIGNvbnN0cnVjdGlvbiApKSk8L2gxPg=="));
            $q_main_page="SELECT
                                      page.data
                                    FROM page
                                    WHERE page.data_key = 'main_page'";
            if ($stmt = self::$db->prepare($q_main_page)) {
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($data);
                $stmt->fetch();
                $stmt->close();
                 if(!empty($data)){
                  $arrVKey = array();
                  $arrNodes= array();
                  $arr[0]['template']=$data;
                  $arr[0]['views']=self::getmainviews($arrVKey,$arrNodes);
                  $arr[0]['arrVKey']=$arrVKey;
                  //$arr[0]['arrNodes']=$arrNodes;

                  $arr[0]['widgets']=self::getwidgets($arrVKey,"",true);
                 // $arr[0]['contents']=self::getmaincontents(array_keys($arr[0]['views']));
                 }
            }
            $res=json_encode($arr);
          } else if (count($value)== 1) {
             $q .= "AND site_contenttype_view.is_this_list = '1'";
             if ($stmt = self::$db->prepare($q)) {
                $stmt->bind_param( "s" ,$value['c1']);
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($c_content_type,$c_title,$c_template,$c_vkey,$c_show_node_title, $c_node_title_automatic);
                $stmt->fetch();
                $stmt->close();

                 if(!empty($c_content_type)){

                  $c_title         = $c_show_node_title  == 1 ? $c_title : "";
                  $contents     =self::getlistcontents($c_content_type,$c_vkey);
                  $arr = array(array('content_type' => $c_content_type, 'title' => $c_title, 'template' => $c_template,'vkey'=>$c_vkey,'showtitle'=>$c_show_node_title, 'automatictitle'=>
                  $c_node_title_automatic, 'contents'=>$contents,'widgets'=>self::getwidgets($c_vkey)));
                  $res=json_encode($arr);
                }else
                {
                  self::helperadr($value['c1']);
                  $radr=$value['c1'];
                   $q_single.=" AND site_content.content_address IN ($radr)";
                    if ($stmt = self::$db->prepare($q_single)) {
                      $stmt->execute();
$stmt->store_result();
                      $stmt->bind_result($c_content_type,$c_title,$c_template,$c_vkey,$c_show_node_title, $c_node_title_automatic);
                      $stmt->fetch();
                      $stmt->close();
                      if(!empty($c_content_type)){

                        $title         = $c_node_title_automatic== 1 ? self::getdinamictitle($radr) : $c_title;
                        $title         = $c_show_node_title  == 1 ? $title : "";
                        $arr = array(array('content_type' => $c_content_type, 'title' => $title, 'template' => $c_template,'vkey'=>$c_vkey,'showtitle'=>$c_show_node_title, 'automatictitle'=>
                        $c_node_title_automatic,'fields'=>self::getfields($c_vkey,$radr),'widgets'=>self::getwidgets($c_vkey,$radr)));
                        $res=json_encode($arr);
                      }
                    }
                }

             }
          } else{
            self::gettemplateadr($value,$g_adr,$r_adr);
            if(self::checkaddress($r_adr)){
            $q .= "AND site_contenttype_view.address = '$g_adr' ";
             if ($stmt = self::$db->prepare($q)) {
                $stmt->bind_param( "s" ,$value['c1']);
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($c_content_type,$c_title,$c_template,$c_vkey,$c_show_node_title, $c_node_title_automatic);
                $stmt->fetch();
                $stmt->close();
                 if(!empty($c_content_type)){
                  $widgetlist= array();
                  $fields      =self::getfields($c_vkey,$r_adr);
                  $widgets   =self::getwidgets($c_vkey,$r_adr);
                  $title         = $c_node_title_automatic== 1 ? self::getdinamictitle($r_adr) : $c_title;
                  $title         = $c_show_node_title  == 1 ? $title : "";
                  $arr = array(array('content_type' => $c_content_type, 'title' => $title, 'template' => $c_template,'vkey'=>$c_vkey,'showtitle'=>$c_show_node_title, 'automatictitle'=>
                    $c_node_title_automatic,'fields'=>$fields,'widgets'=>$widgets));
                  $res=json_encode($arr);
                }
             }
           }
          }
          return $res;
      }

      function getlistcontents($content_type,$vkey){
        $arrContents = array( );
        $q="SELECT
              site_content.content_address,
              site_content.content_title,
              site_content_options_value_default.value AS weight
            FROM site_content
              INNER JOIN site_content_options_value_default
                ON site_content.content_node = site_content_options_value_default.node AND site_content_options_value_default.option_mashine_name = 'c_weight'
            WHERE site_content.content_type = ? AND site_content.content_visible = 1";
              if ($stmt = self::$db->prepare($q)) {
                $stmt->bind_param( "s" ,$content_type);
                $stmt->execute();
$stmt->store_result();
                $stmt->bind_result($content_address,$content_title,$weight);
                while ( $stmt->fetch()) {
                   $weight = $weight !=null ? intval(json_decode(base64_decode($weight))->value) : 0;
                   $arr = array('title' => $content_title , 'address' => $content_address, 'weight'=>$weight );
                   array_push($arrContents, $arr);
                 }
                $stmt->close();
                foreach ($arrContents as $key => $value) {
                  $r_adr=$value['address'];
                  self::helperadr($r_adr);
                  $arrContents[$key]["fields"]=self::getfields($vkey,$r_adr);
                }
              }
              return  $arrContents;
      }

      function gettemplateadr($chapters, &$res,&$res_r){
      $count_chapter =count($chapters);
       $res_a=array();
       $res_b='';
        $i = 0;
        if($count_chapter>1){

            foreach($chapters as $key => $value) {
                if($i>0){$res.='/&';}
                 $res_b.='/'. $value;
                 $i++;
            }
          $val=rtrim(ltrim($res_b,'/'),'/');

          array_push($res_a, $val);

          array_push($res_a, '/'.$val);
          array_push($res_a, $val.'/');
          array_push($res_a, '/'.$val.'/');

          foreach($res_a as $key => $value) {
             $res_r.="'".$value."',";
          }
          $res_r=rtrim($res_r,',');
        }

      }

      function helperadr(&$val){
          $res_a=array();
          array_push($res_a, $val);

          array_push($res_a, '/'.$val);
          array_push($res_a, $val.'/');
          array_push($res_a, '/'.$val.'/');

          $val='';
          foreach($res_a as $key => $value) {
             $val.="'".$value."',";
          }
          $val=rtrim($val,',');
      }

      function checkaddress($radr){
        $res=FALSE;
         $q="SELECT
                  site_content.content_type
                FROM site_content
                WHERE site_content.content_address IN ($radr)
                AND site_content.content_visible = 1";

          if ($stmt = self::$db->prepare($q)) {
              $stmt->execute();
$stmt->store_result();
              $stmt->bind_result($content_type);
              while ($stmt->fetch()) {
                $res=TRUE;
                break;
              }
              $stmt->close();
        }
        return $res;
      }

        function getdinamictitle($radr){
        $res='';
         $q="SELECT
                  site_content.content_title
                FROM site_content
                WHERE site_content.content_address IN ($radr)
                AND site_content.content_visible = 1";

                //var_dump($q); exit();

          if ($stmt = self::$db->prepare($q)) {
              $stmt->execute();
$stmt->store_result();
              $stmt->bind_result($content_title);
              $stmt->fetch();
              $res=$content_title;
              $stmt->close();
        }
        return $res;
      }

      function getfields($vkey,$radr,$node){
         $res=array( );
          $q="SELECT
                  site_contenttype_views_fields.ikey,
                  site_contenttype_views_fields.widget,
                  site_contenttype_views_fields.title,
                  site_content_value.value
                FROM site_contenttype_views_fields
                  LEFT OUTER JOIN site_content_value
                    ON site_contenttype_views_fields.ikey = site_content_value.ikey
                  INNER JOIN site_content
                    ON site_content_value.node_id = site_content.content_node
                WHERE site_contenttype_views_fields.vkey = ?
                AND site_contenttype_views_fields.widget <> 'none'";
                if(!empty($radr)){$q.=" AND site_content.content_address IN ($radr)";}
                if(!empty($node)){$q.=" AND site_content_value.node_id IN (SELECT site_content_main_views.node FROM site_content_main_views
                                                                                                 WHERE site_content_main_views.node = '$node')";}
                $q.="ORDER BY site_contenttype_views_fields.weight";
           if ($stmt = self::$db->prepare($q)) {
              $stmt->bind_param( "s" ,$vkey);
              $stmt->execute();
$stmt->store_result();
              $stmt->bind_result($f_ikey,$f_widget,$f_title,$f_value);
              while ($stmt->fetch()) {
                $fieldobj = array('f_widget' => $f_widget, 'f_title' => $f_title,'f_value'=>json_decode(base64_decode($f_value)));
                array_push($res, $fieldobj);
              }
              $stmt->close();
        }
        return $res;
      }

       function helperIN($valObj){
          $val='';
          if(gettype ($valObj)=='array'){

            foreach($valObj as $key => $value) {
             $val.="'".$value."',";
            }
            $val=rtrim($val,',');
          }elseif (gettype ($valObj)=='string') {
             $val="'".$valObj."'";
          }
          return $val;
      }

      function getwidgets($vkey,$radr,$is_main){
        $vkey=self::helperIN($vkey);
        $res=array( );
        $q="SELECT
              site_contenttype_views_fields.widget,
              site_components_widgets.tcode
            FROM site_contenttype_views_fields
              LEFT OUTER JOIN site_content_value
                ON site_contenttype_views_fields.ikey = site_content_value.ikey
              INNER JOIN site_content
                ON site_content_value.node_id = site_content.content_node
              LEFT OUTER JOIN site_components_widgets
                ON site_contenttype_views_fields.widget = site_components_widgets.widget_key
            WHERE site_contenttype_views_fields.vkey IN ($vkey)
            AND site_contenttype_views_fields.widget <> 'none'";
             if(!empty($radr)){$q.=" AND site_content.content_address IN ($radr)";}
             if($is_main){$q.=" AND site_content_value.node_id IN (SELECT site_content_main_views.node FROM site_content_main_views
                                                                                              WHERE site_content_main_views.view_ikey IN ($vkey))";}
           $q.="  AND site_components_widgets.active = 1
            GROUP BY site_contenttype_views_fields.widget, site_components_widgets.tcode ";

      if ($stmt = self::$db->prepare($q)) {

              $stmt->execute();
$stmt->store_result();
              $stmt->bind_result($w_key,$w_template);
              while ($stmt->fetch()) {
               $res[$w_key]= $w_template;
              }
              $stmt->close();
        }
        return $res;

      }

       function getchcalendar (){
        header('Content-Type: application/json');
         $fname = date("Ymd");

         $cFolder=Registry::get('Cache',null,true).'/calendar/';
          if (!is_dir($cFolder)) {
                  mkdir($cFolder);         
          }

        if (file_exists($cFolder.$fname)) {

            $res= file_get_contents($cFolder.$fname, true);

        }else{

              $contents = file_get_contents("http://www.holytrinityorthodox.com/ru/calendar/calendar.php?lives=1&trp=1&scripture=1");
              $contents ='<span style="font-family: Arial; color: #586778;">'.$contents.'</span> ';
              $contents= iconv("windows-1251", "UTF-8", $contents);
              $contents = str_replace( 'onClick="return popup(this, \'los\')"','',$contents);
              $arrayLinks = array();
              $arrayModals = array();
              $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
              if(preg_match_all("/$regexp/siU", $contents, $matches)) {
                // array_push($arrayLinks, $matches);
                  foreach ($matches[2] as $key => $value) {
                     $contents = str_replace( 'href="'.$value.'"',' ng-click="callbackFn({arg1:'.$key.'})"',$contents);
                     $modalContent =  file_get_contents($value);
                     $modalContent ='<span style="font-family: Arial; color: #586778;">'.$modalContent.'</span>';
                     $modalContent= iconv("windows-1251", "UTF-8", $modalContent);

                     $search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript 
                                                          // Вырезает HTML-теги 
                       "'([\r\n])[\s]+'",                 // Вырезает пробельные символы 
                       "'&(quot|#34);'i",                 // Заменяет HTML-сущности 
                       "'&(amp|#38);'i", 
                       "'&(lt|#60);'i", 
                       "'&(gt|#62);'i", 
                       "'&(nbsp|#160);'i", 
                       "'&(iexcl|#161);'i", 
                       "'&(cent|#162);'i", 
                       "'&(pound|#163);'i", 
                       "'&(copy|#169);'i", 
                       "'&#(\d+);'e");                    // интерпретировать как php-код 
       
                      $replace = array (
                                        "", 
                                        "\\1", 
                                        "\"", 
                                        "&", 
                                        "<", 
                                        ">", 
                                        " ", 
                                        chr(161), 
                                        chr(162), 
                                        chr(163), 
                                        chr(169), 
                                        "chr(\\1)");

                     $modalContent = preg_replace($search, $replace, $modalContent); 
                     $modalContent = preg_replace("'<a[^>]*?>.*?</a>'si","",$modalContent);
                     $modalContent = preg_replace("'<img[^>]*?>'si","",$modalContent);
                     $modalContent = preg_replace("'<link[^>]*?>'si","",$modalContent);
                

                    // $modalContent = str_replace( '<a class="main" href="#" onclick="window.close()">Close window</a>','',$modalContent);

                     $arrayModals['m'.$key]=array('title' => $matches[3][$key], 'content' => $modalContent, 'templateUrl'=> 'modal-calendar.html', 'html' => true);
                  }
                  // $matches[2] = array of link addresses
                  // $matches[3] = array of link text - including HTML code
              }
              $res=json_encode(array('template' => base64_encode($contents), 'modals' => $arrayModals ));

              file_put_contents($cFolder.$fname,$res);

        } 

        return  $res;
       }

       function get_html($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
      }

      
}
?>