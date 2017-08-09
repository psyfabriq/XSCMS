<?php
require_once 'system/libs/compressor/cssmin.php';
require_once 'system/libs/compressor/jsmin.php';
 
class Compressor
{
	private static $unix_timestamp=null;
	private static $adir=null;

	function __construct()
	{
		self::$unix_timestamp = time();
		self::$adir = "cache";
		self::run();
	}

	private function run(){
		//		Create target directory and write Apache htaccess file when no directory found
		if (!file_exists(self::$adir))
			{
				mkdir(self::$adir) && chmod(self::$adir, 0777);
				$htaccess='Options All -Indexes'.PHP_EOL;
				$htaccess.='AddType text/css cssgz'.PHP_EOL;
				$htaccess.='AddType text/javascript jsgz'.PHP_EOL;
				$htaccess.='AddEncoding x-gzip .cssgz .jsgz'.PHP_EOL;
				$htaccess.='# for all files in min directory'.PHP_EOL;
				$htaccess.='FileETag None'.PHP_EOL;
				$htaccess.='# Cache for a week, attempt to always use local copy'.PHP_EOL;
				$htaccess.='<IfModule mod_expires.c>'.PHP_EOL;
				$htaccess.='  ExpiresActive On'.PHP_EOL;
				$htaccess.='  ExpiresDefault A604800'.PHP_EOL;
				$htaccess.='</IfModule>'.PHP_EOL;
				$htaccess.='<IfModule mod_headers.c>'.PHP_EOL;
				$htaccess.='  Header unset ETag'.PHP_EOL;
				$htaccess.='  Header set Cache-Control "max-age=604800, public"'.PHP_EOL;
				$htaccess.='</IfModule>'.PHP_EOL;
				file_put_contents(self::$adir.'/.htaccess',$htaccess);			//write initial htaccess file
				mkdir(self::$adir.'/css') && chmod(self::$adir.'/css', 0777);
				mkdir(self::$adir.'/js')  && chmod(self::$adir.'/js', 0777);
			}
	}

     private function getTimeStamp($who,$what){
     	$f=self::$adir."/compress_hashkeys.json";
     	if(!file_exists($f)){
     		$j = array('css' =>array(),'js' =>array());
     		file_put_contents($f, json_encode($j),LOCK_EX);
     	}
     	$json = json_decode(file_get_contents($f), true);
     	if(!empty($json[$who][$what])){
     		return  $json[$who][$what];
     	}else{return false;}
     }

     private function setTimeStamp($who,$what,$value){
     	$f=self::$adir."/compress_hashkeys.json";
     	$json = json_decode(file_get_contents($f), true);
     	$json[$who][$what]=$value;
     	file_put_contents($f, json_encode($json),LOCK_EX);
     }

     public function getOldFile($type,$key,$isgz=false){
     	 $gTimeStamp=self::getTimeStamp($type,$key);
     	 $adir=self::$adir.'/'.$type.'/';
     	 if ($isgz==true){
			$gz='gz';
	    }else{$gz=null;}
     	return $adir.$type.'_'.$key.'_'.$gTimeStamp.'.'.$type.$gz;
     }
	 public function init($type,$key,$arrayValues,$update=false){

        $gTimeStamp=self::getTimeStamp($type,$key);
       
       if($update==false){
		 	if($gTimeStamp==false){
		 		self::file_compress($type.'_'.$key.'.'.$type,$arrayValues,$key);
		 	}
		 	else{
		 		self::$unix_timestamp=$gTimeStamp;
		 	}
	   }else{

	   	    self::file_compress($type.'_'.$key.'.'.$type,$arrayValues,$key);
	   }

	 	//Clean old files !!

		$adir=self::$adir.'/'.$type.'/';
		self::$unix_timestamp.='';		//make a string

		if (file_exists($adir) && $dh = opendir($adir))
			{
			while($fn = readdir($dh)) if ($fn[0] != ".")
				{
				if (strpos($fn,$key.'_'.self::$unix_timestamp)===false)
					{
						if(strpos($fn,$key)!==false)
						  $df=$adir.$fn;	
					      unlink($df);
					}
				}
			closedir($dh);
			}

		else {echo 'failed to open ',$adir,' ',date("r"),PHP_EOL;}

		if (stripos($_SERVER['HTTP_ACCEPT_ENCODING'],'GZIP')!==false){
			$gz='gz';
	    }else{$gz=null;}

	    return $adir.$type.'_'.$key.'_'.self::$unix_timestamp.'.'.$type.$gz;
	}

	private function file_compress($file_name,$file_input,$key) {
		//global self::$unix_timestamp,self::$adir;
		$pos=strrpos($file_name,'.');				//get last . in file name
		if ($pos==false) die ('illogical response from strrpos');
		$fn=substr($file_name,0,$pos).'_'.self::$unix_timestamp.substr($file_name,$pos);	//put timestamp into file name
		$fl=null;						//clear file data variable
	
		foreach($file_input as $value)				//merge files in the group

			$fl.= $value.' ';

		$len_orig=strlen($fl);
		if (strtolower(substr($file_name,$pos+1,2)) == 'js'){
			$fl = JSMin::minify($fl);			//minify js
		    $type='js';
		}
		else{
			$fl = CssMin::minify($fl);			//minify css
			$type='css';
		}

		$len_minify=strlen($fl);
	    $gzdata=gzencode ($fl,9);				//gzip

	 	file_put_contents (self::$adir.'/'.$type.'/'.$fn,$fl);			 	//put out minified, non gzipped version
	   	file_put_contents (self::$adir.'/'.$type.'/'.$fn.'gz',gzencode ($fl,9));	//put out gzipped version
	   	self::setTimeStamp($type,$key,self::$unix_timestamp.'');
	}
}
?>