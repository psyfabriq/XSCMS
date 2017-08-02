<?php
include_once ("system/backup/datehour.inc.php");
include_once ("system/backup/createzipfile.inc.php");
include_once ("system/backup/mysqldump.inc.php");

class backup
{
	/*************/
	/* Variables */
	/*************/
	var $etiqueta;				     // First part of generated filename
	var $dir_origen;			     // Source directory (add a slash at the end)
	var $dir_destino;			     // Destination directory
	var $fich_genname;		          // Generated filename
	var $bd_host;					// DB host
	var $bd_usr;					// DB username
	var $bd_pass;				// DB password
	var $bd_namedb;				// Name of database name to be saved
	var $error;						// Last error happened

	/********************/
	/* Setter functions */
	/********************/
	function set_etiqueta ($valor)
	{
		$this->etiqueta = $valor;
	}
	function set_dir_origen ($valor)
	{
		$this->dir_origen = $valor;
	}
	function set_dir_destino ($valor)
	{
		$this->dir_destino = $valor;
	}
	function set_bd_host ($valor)
	{
		$this->bd_host = $valor;
	}
	function set_bd_usr ($valor)
	{
		$this->bd_usr = $valor;
	}
	function set_bd_pass ($valor)
	{
		$this->bd_pass = $valor;
	}
	function set_bd_namedb ($valor)
	{
		$this->bd_namedb = $valor;
	}

	function get_error ()
	{
		return $this->error;
	}


	function inicializar ()
	{
		$this->error = "";
		$f_h = new date_hour;
		$this->fich_genname = $this->dir_destino."/".$this->etiqueta."_".$f_h->hour_bd ().
			"_".$f_h->date_bd ();
	}

	function backup_files ()
	{
		$this->inicializar ();
		$fich_genname = $this->fich_genname.".zip";

		$zip = new CreateZipFile;
		 $this->make_archive ($this->dir_origen, $zip);
		if (!$zip->addFile ($zip->getZippedfile (), $fich_genname))
		{
			$this->error = "<p>Error writing file $fich_genname</p>";
			$res = false;
		}
		else
		{
			$zip->forceDownload ($fich_genname);
			// Deletes the file (doesn't work without write permissions)
			chmod ($fich_genname, 0777);
			@unlink ($fich_genname);
			$res = true;
		}
		return $res;
	}

	function make_archive ($dir, &$zip, $extdir = "")
	{
		if (is_dir ($dir))
		{
			if ($dh = opendir ($dir))
			{
				while (($file = readdir ($dh)) !== false)
				{
					if ($file != "." && $file != "..")
					{
						$c = 0;
						$dir_zip = substr ($dir, $c);
						if (is_dir ($dir.$file))
						{
							$zip->addDirectory ($dir_zip.$file);
							$this->make_archive ($dir.$file."/", $zip, $extdir.$file."/");
						}
						else
						{
							$fileContents = file_get_contents ($dir.$file);
							$zip->addFile ($fileContents, $dir_zip.$file);

						}
					}
				}
				closedir ($dh);
			}
		}
		return true;
	}

	function backup_mysql ()
	{
		$this->inicializar ();
		$fich_genname = $this->fich_genname.".dmp";
	  $mysql = new MYSQL_DUMP ($this->bd_host, $this->bd_usr, $this->bd_pass);
		$mysql->setDBHost($this->bd_host, $this->bd_usr, $this->bd_pass);
		if (!$mysql->save_sql ($mysql->dumpDB ($this->bd_namedb), $fich_genname))
		{
			$this->error = "<p>Error writing file $fich_genname</p>";
			$res = false;
		}
		else
		{
			$mysql->download_sql ($fich_genname);
			// Deletes the file (doesn't work without write permissions)
			chmod ($fich_genname, 0777);
			@unlink ($fich_genname);
			$res = true;
		}
		return $res;
	}
}
?>