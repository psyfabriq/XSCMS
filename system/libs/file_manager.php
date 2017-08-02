<?php

class filemanager
{

	function globr($sDir, $sPattern, $nFlags = NULL)
	{
		$sDir = escapeshellcmd($sDir);

		$aFiles = glob("$sDir/$sPattern", $nFlags);

		foreach (@glob("$sDir/*", GLOB_ONLYDIR) as $sSubDir)
		{
			$aSubFiles = $this->globr($sSubDir, $sPattern, $nFlags);
			$aFiles = array_merge($aFiles, $aSubFiles);
		}

		return $aFiles;
	}//end function


	function parentDir()
	{
		$parentDir = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/';
		return $parentDir;
	}


	function changeMode($file,$octal)
	{
		chmod($file,$octal);
		return true;
	}


	function chmod_R($path, $filemode)
	{
		if (!is_dir($path))
		return chmod($path, $filemode);

		$dh = opendir($path);
		while ($file = readdir($dh))
		{
			if($file != '.' && $file != '..')
			{
				$fullpath = $path.'/'.$file;
				if(!is_dir($fullpath))
				{
					if (!chmod($fullpath, $filemode))
					return FALSE;
				}
				else
				{
					if (!$this->chmod_R($fullpath, $filemode))
					return FALSE;
				}
			}
		}

		closedir($dh);

		if(chmod($path, $filemode))
		return TRUE;
		else
		return FALSE;
	}


	function chmodnum($mode)
	{
		$mode = str_pad($mode,9,'-');
		$trans = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');
		$mode = strtr($mode,$trans);
		$newmode = '';
		$newmode .= $mode[0]+$mode[1]+$mode[2];
		$newmode .= $mode[3]+$mode[4]+$mode[5];
		$newmode .= $mode[6]+$mode[7]+$mode[8];
		return $newmode;
	}


	function recurse_chown_chgrp($mypath, $uid, $gid)
	{
		$d = opendir ($mypath) ;
		while(($file = readdir($d)) !== false)
		{
			if ($file != "." && $file != "..")
			{
				$typepath = $mypath . "/" . $file ;
				if (filetype ($typepath) == 'dir')
				{
					$this->recurse_chown_chgrp ($typepath, $uid, $gid);
				}

				chown($typepath, $uid);
				chgrp($typepath, $gid);
			}
		}

	}


	function copyr($source, $dest)
	{
		// Simple copy for a file
		if (is_file($source))
		{
			return copy($source, $dest);
		}
		// Make destination directory
		if (!is_dir($dest))
		{
			mkdir($dest);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read())
		{
			// Skip pointers
			if ($entry == '.' || $entry == '..')
			{
				continue;
			}
			// Deep copy directories
			if ($dest !== "$source/$entry")
			{
				$this->copyr("$source/$entry", "$dest/$entry");
			}
		}
		// Clean up
		$dir->close();
		return true;
	}


	function df($drive = "C:")
	{
		if(PHP_OS=='WINNT' || PHP_OS=='WIN32')
		{
			$df = disk_free_space($drive);
		}
		else
		{
			$df = disk_free_space("/");
		}
		return $df;
	}

}//end class
?>