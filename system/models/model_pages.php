<?php
  class Model_Pages extends Model
  {
       function __construct()
      {
        parent::__construct();
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
              return "error";
            }
      }

      function getpage(){

        $query = "SELECT data FROM adm_pages  WHERE gencode = ?";
        if ($stmt = parent::$db->prepare($query)) {
            $stmt->bind_param("s", parent::$value);
            $stmt->execute();
$stmt->store_result();
            $stmt->bind_result($data);
            $stmt->fetch();
            $stmt->close();
            return base64_decode($data);
        }

      }
  }