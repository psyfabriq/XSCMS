<?php 

define('DB_HOST','10.0.10.12');
define('DB_LOGIN','sedsystem');
define('DB_PASSWORD','ermak9056');
define('DB_NAME','sed_system');

/* create a connection object which is not connected */
$mysqli = mysqli_init();

/* set connection options */
$mysqli->options(MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=0");
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);

/* connect to server */
$mysqli->real_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

printf ("Connection: %s\n.", $mysqli->host_info);

$mysqli->close();


/*
$db = mysqli_connect (DB_HOST,DB_LOGIN,DB_PASSWORD,DB_NAME);
mysqli_query($db, "SET NAMES utf8");
mysqli_query($db, "SET CHARACTER SET utf8");
mysqli_query($db, "SET character_set_client = utf8");
mysqli_query($db, "SET character_set_connection = utf8");
mysqli_query($db, "SET character_set_results = utf8");
if (!$db){echo "Подключение не выполнено!";}else {echo "Все ровно!"."<br>";}

$query = "SELECT data_container AS 'data',
                 alias_container AS 'alias',
                 key_container   AS 'keyc'
    FROM css_container_general   ";
/*
    if ($stmt = $db->prepare($query)) {
      //$stmt->bind_param("s", "4802B0FF-A0BC-3381-52F7-F4722C202830");
      $stmt->execute();
      $stmt->bind_result($code_container);
      $stmt->fetch();
      $stmt->close();
      echo $code_container;
      
    }
*/

//$sql = "SELECT * FROM adm_config";
/*
$r = mysqli_query ($db, $query);

while ($s = mysqli_fetch_array($r, MYSQLI_ASSOC))
{
	echo $s['keyc']."<br>";
	
	
}

//hdvid505_admin

//NsNcDa8sX4Kb9vZh
*/
?>