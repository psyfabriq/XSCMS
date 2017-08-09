<?php
class Model_Resource extends Model{
  function __construct()
  {
    parent::__construct();
  }

  function getjs(){
    $query = "SELECT code_container AS 'data'
    FROM js_container WHERE js_container.key_container=?
    UNION
    SELECT data_container AS 'data'
    FROM js_container_general  WHERE js_container_general.key_container=? ";
    if ($stmt = parent::$db->prepare($query)) {
      $stmt->bind_param("ss", parent::$value,parent::$value);
      $stmt->execute();
$stmt->store_result();
      $stmt->bind_result($code_container);
      $stmt->fetch();
      $stmt->close();
      return base64_decode($code_container);
    }
  }

  function getcss(){
    $query = "SELECT data_container AS 'data'
    FROM css_container_general  WHERE css_container_general.key_container=? ";
    if ($stmt = parent::$db->prepare($query)) {
      $stmt->bind_param("s", parent::$value);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($code_container);
      $stmt->fetch();
      $stmt->close();
      return base64_decode($code_container);
    }
  }

  function getfonts() {
        $arrayResult = array('header' => '', 'result' => false, 'data' => '');
        $query = "SELECT header_container,data_container FROM fonts_container_general where fonts_container_general.key_container=?";
        if ($stmt = parent::$db->prepare($query)) {
            $stmt->bind_param("s", parent::$value);
            $stmt->execute();
$stmt->store_result();
            $stmt->bind_result($header_container, $data_container);
            $stmt->fetch();
            if(!empty($header_container)&&!empty($data_container)){
               $arrayResult = array('header' => $header_container, 'result' => true, 'data' => base64_decode($data_container));
            }

            $stmt->close();
        }

        return $arrayResult;
  }

  function doAction($jinit , $jdata){
   header('Content-Type: application/json');
   $ainit=json_decode($jinit,true);
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
  function getjskeys(){
    header('Content-Type: application/json');
    $arrayKeys = array();
    $host     = 'http://'.$_SERVER['HTTP_HOST'].'/';
    $query = "SELECT
    js_container.key_container
    FROM adm_config
    RIGHT OUTER JOIN js_container
    ON adm_config.config_type = js_container.who_container
    WHERE adm_config.data_key = ?";

    if ($stmt = parent::$db->prepare($query)) {
      $stmt->bind_param("s", parent::$value);
      $stmt->execute();
$stmt->store_result();
      $stmt->bind_result($key_container);
      while ($stmt->fetch()) {
             $js_path  = '../res/?js&'.$key_container;
              array_push($arrayKeys, $js_path);
      }
      $stmt->close();
    }
    return json_encode($arrayKeys);
  }
}
