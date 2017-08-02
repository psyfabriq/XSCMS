<?php
class Model_General extends Model{

  function __construct()
  {
    parent::__construct();
  }

  function getjs($alias){
    $query = "SELECT   key_container FROM js_container_general WHERE js_container_general.alias_container =? ";
    if ($stmt = parent::$db->prepare($query)) {
      $stmt->bind_param("s", $alias);
      $stmt->execute();
$stmt->store_result();
      $stmt->bind_result($key_container);
      $stmt->fetch();
      $stmt->close();
      if(empty($key_container)){
        $guid = self::getGUID();
        self::setjs($alias,$guid);
        return $guid;
      }
      else{return $key_container;}
    }
  }

  function setjs($alias,$guid){
    $all_query_ok=true;
    $file = base64_encode(file_get_contents($alias, FILE_USE_INCLUDE_PATH));

   // $alias    =trim(mysql_escape_string (trim($alias)));

    $query = "INSERT INTO js_container_general (`alias_container`, `key_container`, `data_container`) VALUES ('$alias','$guid','$file');";
    try {
      parent::$db->autocommit(FALSE);
      parent::$db->query($query) ? null : $all_query_ok=false;
      if ($all_query_ok==false) {
        parent::$db->close();
      }
      parent::$db->close();
      mt_srand((double)microtime()*10000);
    }
    catch  (Exception $e) {
      parent::$db->rollback();
      parent::$db->close();
    }
  }

  function getcss($alias){
   $query = "SELECT   key_container FROM css_container_general WHERE css_container_general.alias_container =? ";
   if ($stmt = parent::$db->prepare($query)) {
    $stmt->bind_param("s", $alias);
    $stmt->execute();
$stmt->store_result();
    $stmt->bind_result($key_container);
    $stmt->fetch();
    $stmt->close();
    if(empty($key_container)){
      $guid = self::getGUID();
      self::setcss($alias,$guid);
      return $guid;
    }
    else{return $key_container;}
  }
}

function setcss($alias,$guid){
  $all_query_ok=true;
  $file = base64_encode(file_get_contents($alias, FILE_USE_INCLUDE_PATH));


  $query = "INSERT INTO css_container_general (`alias_container`, `key_container`, `data_container`) VALUES ('$alias','$guid','$file');";
  try {
    parent::$db->autocommit(FALSE);
    parent::$db->query($query) ? null : $all_query_ok=false;
    if ($all_query_ok==false) {
      parent::$db->close();
    }
    parent::$db->close();
    mt_srand((double)microtime()*10000);
  }
  catch  (Exception $e) {
    parent::$db->rollback();
    parent::$db->close();
  }
}

function getGUID(){
  if (function_exists('com_create_guid')){
    return com_create_guid();
  }else{
    mt_srand((double)microtime()*10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $uuid =
    substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12);
    return $uuid;
  }
}

  function getListJS($what){
     $arrayRoot = array();
      $query = "SELECT
                      loader_js_css.path
                    FROM loader_js_css
                    WHERE loader_js_css.type = 'js'
                    AND loader_js_css.`where` = ?
                    AND loader_js_css.active = 1
                    ORDER BY loader_js_css.weght";
      if ($stmt = parent::$db->prepare($query)) {
          $stmt->bind_param("s",$what);
          $stmt->execute();
$stmt->store_result();
          $stmt->bind_result($path);
          while ($stmt->fetch()) {
              array_push($arrayRoot, $path);
          }
          $stmt->close();
      }
      return $arrayRoot;
  }
  function getListCSS($what){
      $arrayRoot = array();
      $query = "SELECT
                        loader_js_css.path
                      FROM loader_js_css
                      WHERE loader_js_css.type = 'css'
                      AND loader_js_css.`where` = ?
                      AND loader_js_css.active = 1
                      ORDER BY loader_js_css.weght";
      if ($stmt = parent::$db->prepare($query)) {
          $stmt->bind_param("s",$what);
          $stmt->execute();
$stmt->store_result();
          $stmt->bind_result($path);
          while ($stmt->fetch()) {
              array_push($arrayRoot, $path);
          }
          $stmt->close();
      }
      return $arrayRoot;
  }
}