<?php
	class Model_Admin extends Model
	{
		protected static $uo   =null;

	    function __construct()
		  {
		    parent::__construct();
		    self::$uo = Registry::get('USEROBJ');
		    Registry::set('xsrf', '<script>angular.module("XSCMS_ADMIN_DASHBOARD").constant("CSRF_TOKEN", "'.$_SESSION['XSRF'].'");</script>'); 
		      
		  }

		 public function doAction($jinit , $jdata){

		  	    $ainit=json_decode($jinit,true);
    	                 $adata=json_decode($jdata,true);

			  	if(method_exists($this, $ainit['what']))
		        {
		        	parent::$value=$ainit['value'];
		            return $this->$ainit['what']();
		        }
		        else{
		        	Controller::returnMessage("Not found ".$ainit['what']." code !!! ", "Danger" ,"OPS");
		        }
		  }

		  function getfaq(){
		  	
		  	$array_root      = array();

		  	$query = "SELECT title AS title,
			                  config_type AS systemname
			          FROM adm_config ORDER BY data_key";
			$db=parent::$mdb;
            $faq = $db->faq;  
            	if ($stmt = parent::$db->prepare($query)) {

            		$stmt->execute();
$stmt->store_result();
			        $stmt->bind_result($title, $systemname);
			        $listconstructions      = array();
			        $listdata               = array();

			         while ($stmt->fetch()) {
			         	$itemid = parent::generateCode(13);
			         	$item= array( 'name' => "$title", 'itemid' => "$itemid", 'categories'=> []);
			    	    
					    	$cursor=$faq->findOne(array('data_key' => $systemname));
		                     if(!empty($cursor)){
		                     	$listdata["$itemid"] = $cursor['value'];
		                        //$item['value']  =$cursor['value']; 
		                       //$item['categories']=$cursor['categories'];
		                     }
					    	
					        $listconstructions["$systemname"]=$item;
			         }
			         $stmt->close();

			         $array_root['list']=$listconstructions;
			         $array_root['data']=$listdata;

			         Controller::returnData(json_encode($array_root));

            	}          
		  }

		  function getmenu()
		  { 
		  	$uo = Registry::get('USEROBJ');
		  	$ur = $uo->getUserRoles();
		  	$ids = join(',', $ur);  

		  

		  	header('Content-Type: application/json');
		  	$rootArray =array();
		  	$childArray=null;
		  	$menu_title_old='';

		  	$query = "SELECT
						  admt.title AS menu_title,
						  admt.data_key AS data_key
						FROM adm_typmenu admt
						  LEFT OUTER JOIN menu_access
						    ON admt.data_key = menu_access.idi
						WHERE menu_access.idr IN ($ids)
						GROUP BY admt.data_key
						ORDER BY admt.weight";
      
			if ($stmt = parent::$db->prepare($query)) {
			    $stmt->execute();
$stmt->store_result();
			    $stmt->bind_result($menu_title,$data_key);
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

		  function getpage()
		  {
		  	if ($stmt = parent::$db->prepare("SELECT data FROM page WHERE data_key=?")) {
		  	    $value="admin";
			    $stmt->bind_param("s", $value);
			    $stmt->execute();
$stmt->store_result();
			    $stmt->bind_result($district);
			    $stmt->fetch();
			    $stmt->close();
			    return $district;
			}
		   }

		    function getCategory($data_key,$parent_id){
		    	$uo = Registry::get('USEROBJ');
		     	$ur = $uo->getUserRoles();
		     	$ids = join(',', $ur); 
		        $arrayRoot= array();

			       $query = "SELECT
							  adm_menu.title,
							  adm_menu.url,
							  adm_menu.weight,
							  adm_menu.id
							FROM adm_menu
							  RIGHT OUTER JOIN menu_access
							    ON adm_menu.item_key = menu_access.idi AND adm_menu.data_key = menu_access.data_key
							WHERE adm_menu.data_key = ? AND adm_menu.parent_id = ? AND menu_access.idr IN ($ids)
							ORDER BY adm_menu.weight";

			            if ($stmt = parent::$db->prepare($query)) {
			                $stmt->bind_param("si", $data_key,$parent_id);
			                $stmt->execute();
$stmt->store_result();
			                $stmt->bind_result($titlen, $url, $weight, $id);
			                while ($stmt->fetch()) {
			                    $arrayItemn = array('name'=> $titlen,
			                                        'url'=> '#'.$url,
			                                        'id'=> $id,
			                                        'links'=> []);

			                    array_push($arrayRoot, $arrayItemn);

			                }
			                $stmt->close();

			                if (!empty($arrayRoot)){
			                     foreach ($arrayRoot  as $key => $category) {
			                         $arrayRoot[$key]['links']=$this->getCategory($data_key,$arrayRoot[$key]['id']);
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

		  function getprofile (){
		  	header('Content-Type: application/json');

		  	$uid=self::$uo->getUserID();
            
		  	$query = "SELECT
					  auth_users.real_name AS uname,
					  auth_users.photo AS photo,
					  auth_users.p,
					  auth_users.phototype,
					  auth_users.photosize
					FROM auth_users
					WHERE auth_users.user_id = ?";
			if ($stmt = parent::$db->prepare($query)) {
				$stmt->bind_param("s", $uid);
				$stmt->execute();
$stmt->store_result();
				$stmt->bind_result($uname, $photo, $postfix, $phototype, $photosize);
				//$stmt->fetch();
				if($stmt->fetch()){
					$data = base64_encode(file_get_contents($photo));
					$item = array(
						          'uname' => $uname , 
						          'photo' => 'data:'.$phototype.';base64,'.$data,
						          //'filesize' => $photosize, 
						         //'filetype' => $phototype
						         );
				}else{
					$item = array();
				}
				$stmt->close();
				return json_encode($item);
			}

		  } 

		  function getconfig()
		  {
		   header('Content-Type: application/json');
                $query = "SELECT
						  adm_config.data_key AS url,
						  adm_config.title,
						  adm_config.config_type,
						  GROUP_CONCAT(adm_config_additional.config) AS additional
						FROM adm_config
						  LEFT OUTER JOIN adm_config_additional
						    ON adm_config.config_type = adm_config_additional.config_type
						WHERE adm_config.data_key = ?
						GROUP BY adm_config.config_type
						ORDER BY adm_config.weight";

			if ($stmt = parent::$db->prepare($query)) {
				if(empty(parent::$value)){parent::$value="adm_dashboard";}
			    $stmt->bind_param("s", parent::$value);

			    $stmt->execute();
$stmt->store_result();
			    $stmt->bind_result($url, $title, $config_type, $additional);
			    $district=array();
			    while ($stmt->fetch()) {
			    	$item= array('content_type' => "$config_type" , 'title' => "$title" );
			    if( $additional!=null){
			    		$array_additional=json_decode("{".$additional."}",true);
			    		$item = array_merge($item, $array_additional);
			    	}
			        array_push($district, $item);
			    }
			    $stmt->close();
			    if(!empty($district)){

			    	return json_encode($district);
			    }
			    else{return '[{"content_type" : "404", "title" : "Not Found ;("}]';}
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

		  function getblocks()
	      {
			  $arr = array();
		      $q_poligons="SELECT
		                    site_template_engine_poligons.po_mashine_name
		                  FROM site_template_engine
		                    RIGHT OUTER JOIN site_template_engine_poligons
		                      ON site_template_engine.te_key = site_template_engine_poligons.te_key_parent
		                  WHERE site_template_engine.te_type = 'admin'";
		        if ($stmt = parent::$db->prepare($q_poligons)) {
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
								  AND site_template_engine.te_type = 'admin'
								  AND site_blocks.active = 1
								  ORDER BY site_blocks.weight";

		          if ($stmt = parent::$db->prepare($q_bloks)) {
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


		           if ($stmt = parent::$db->prepare($q_bloks_additionals)) {
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

		  function gettemplate()
		  {
		       header('Content-Type: application/json');
		  	if(parent::$value!='404'){

				if ($stmt = parent::$db->prepare("SELECT data, class FROM adm_template WHERE data_key=? ")) {

				    $stmt->bind_param("s", parent::$value);
				    $stmt->execute();
$stmt->store_result();
				    $stmt->bind_result($district,$itemclass);
				    $stmt->fetch();
				    $stmt->close();
				    if(!empty($district)){
				    	if(empty($itemclass)){$itemclass="col-md-12";}
					    if(!empty($district)){

						    $html=html_entity_decode(base64_decode($district));
						    $this->getScriptTemplates($html);

						    $html = preg_replace("/[\r\n]*/","",$html);
		                    $html = preg_replace('/ {2,}/',' ',$html);
		                    $html = str_replace("\'","'",$html);

		                    $html_array = array('Template'=>base64_encode($html),'class'=>$itemclass);
		                    $json_html  = json_encode($html_array);
					        return   $json_html;
				        
				        }else{ goto a;}
				    }
				}


			}

        a:
	        $error404=base64_encode('<div class="box box-danger"><div class="box-header"><h3 class="box-title">[[content.title]] </h3></div></div>');
	    	return '{ "Template": "'.$error404.'", "class": "col-md-12"}';

		  }

		  function getScriptTemplates(&$html){

		  	if ($stmt = parent::$db->prepare("SELECT adm_pages.data FROM adm_pages WHERE adm_pages.isstempates <> 0 AND adm_pages.stempates LIKE CONCAT('%', ?, '%') ")) {
		  		    $stmt->bind_param("s", parent::$value);
				    $stmt->execute();
$stmt->store_result();
				    $stmt->bind_result($district);
				    while ($stmt->fetch()) {
				    	if(!empty($district)){
				    	   $html.=html_entity_decode(base64_decode($district));
				        }
				    }

				    
		  	}

		  }

		  function getJS(){
		  	$keys=array();
		  	$query = "SELECT key_container FROM  js_container";
			if ($stmt = parent::$db->prepare($query)) {
			    $stmt->execute();
$stmt->store_result();
			    $stmt->bind_result($key_container);
			    while ($stmt->fetch()) {
			    	array_push($keys, $key_container);
			    }
			    $stmt->close();
			}
			return $keys;
		  }
		  function getdebug()
		  {   //header('Content-Type: application/json');
		  	//Global $Debug;
		  	//$html_json=$Debug->getLogs();
		  	//return $html_json;
		  }

	}
?>