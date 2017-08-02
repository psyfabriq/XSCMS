<?php 

   define('DB_HOST','10.0.10.12');
   define('DB_LOGIN','sedsystem');
   define('DB_PASSWORD','sed9018');
   define('DB_NAME','sedsystem');
   define('DB_PORT', 27017);

   try {
           $server = "mongodb://".DB_LOGIN.":".DB_PASSWORD."@".DB_HOST.":".DB_PORT."/".DB_NAME;
            $conn_m      = new MongoClient($server);
            if($conn_m->connected){
              $db = $conn_m->selectDB(DB_NAME);
        
              echo "IS CONNECTED !!!";
            }
            else{
              echo "NO CONNECTED !!!";
            }
        
      } catch (Exception $e) {
        echo $e ;
      }


?>