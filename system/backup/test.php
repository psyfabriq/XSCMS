<html>
<head>
<title>Testing Backup</title>
</head>
<body>
<?
	// Includes the class Backup
	include ("system/backup/backup.inc.php");

	// Crea the object
	$backup = new backup;

	// Sets the parameters
	$backup->set_etiqueta ("test");
	$backup->set_dir_origen ("src_dir/");
	$backup->set_dir_destino ("dest_dir");
	$backup->set_bd_host ("host");
	$backup->set_bd_usr ("user");
	$backup->set_bd_pass ("password");
	$backup->set_bd_namedb ("db_name");

	// Evaluates the parameter modo
	switch ($_GET["modo"])
	{
		// Compress the directory specified by set_dir_origen () and downloads
		// the zip file
		case "files":
			$backup->backup_files ();
		break;
		// Dumps DB into a file and downloads it
		case "db":
			$backup->backup_mysql ();
		break;
		// Shows backup options
		case "":
			echo "<p><a href=\"?modo=files\">Files</a></p>";
			echo "<p><a href=\"?modo=db\">DB</a>";
		break;
	}
?>
</body>
</html>