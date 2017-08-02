<?php

class Debug {
	private $Events = array();
	private $LogFolder = false;
	private $WriteFileOnAnyError = false;
	private $write = false;

	private $StartTime;
	private $WasErrors = false;

	public function __construct($LogFolder = null, $WriteFileOnAnyError = false, $clean=false) {


		$this->StartTime = time();
		$this->AddMessage('Script start', null, true);

		if($LogFolder != null && is_dir($LogFolder)) {
			$this->LogFolder = $LogFolder;
			$this->WriteFileOnAnyError = $WriteFileOnAnyError;
		}

		set_error_handler(array(&$this, 'ErrorHandler'));
	}

	public function CleanLogs(){
		$LogFolder=$this->LogFolder;
		$uid=Session::get('UID');
		if($LogFolder!=null && !empty($uid)){
		    $files = glob($LogFolder.'/*');
			foreach($files as $file){
			  if(is_file($file))
			  	if (strpos($file,'_'.Session::get('UID'))!==false)
			    unlink($file);
			}
		}
	}
	function escapeJsonString($value) {
	    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
	    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
	    $result = str_replace($escapers, $replacements, $value);
	    return $result;
    }
	public function getLogs(){
		$LogFolder=$this->LogFolder;
		$html_array_root=array();
		$uid=Session::get('UID');
		if($LogFolder!=null && !empty($uid)){
		    $files = glob($LogFolder.'/*');
			foreach($files as $file){
			  if(is_file($file))
			  	if (strpos($file,'_'.Session::get('UID'))!==false){
                    array_push($html_array_root,  '../'.$file );
			  	}
			}
		}
		$html_array_root=array_unique($html_array_root);
		$json_html  = json_encode($html_array_root);
		return $json_html;
	}
	public function ErrorHandler($errno, $errstr, $errfile, $errline) {
	    $this->AddMessage('Error '.$errno.'!', 'Error <strong>'.$errno.'</strong>! <strong>'.$errstr.'</strong> in file <strong>'.$errfile.'</strong> on line <strong>'.$errline.'</strong>.', true, 1);
	    $this->WasErrors = true;
	    if($this->WasErrors == true && $this->WriteFileOnAnyError == true) $this->OutputToFile(null,true);
	    return true;
	}

	public function AddMessage($Title, $Text = null, $AddPredefinedVariables = false, $EventID = 0,$AddEventHTML=false) {
		if($AddPredefinedVariables == true) {
			$PV = array();
			foreach($_SERVER as $k => $v)  $PV[] = '$_SERVER[\''.$k.'\'] = '.$v;
			foreach($_GET as $k => $v)     $PV[] = '$_GET[\''.$k.'\'] = '.$v;
			foreach($_POST as $k => $v)    $PV[] = '$_POST[\''.$k.'\'] = '.$v;
			foreach($_COOKIE as $k => $v)  $PV[] = '$_COOKIE[\''.$k.'\'] = '.$v;
			foreach($_FILES as $k => $v)   $PV[] = '$_FILES[\''.$k.'\'] = '.$v;
			foreach($_ENV as $k => $v)     $PV[] = '$_ENV[\''.$k.'\'] = '.$v;
		} else {
			$PV = null;
		}

		$this->Events[] = array('eventid' => $EventID, 'timestamp' => time(), 'title' => $Title, 'text' => $Text, 'pv' => $PV);
		if($this->WasErrors == true && $this->WriteFileOnAnyError == true) $this->OutputToFile(null,$AddEventHTML);
	}

	public function WriteImportant(){
		$this->write = true;
	}

	public function OutputToFile($File = null,$AddEventHTML=true) {

		$EventHTML = null;
		$DivID = 0;

		foreach($this->Events as $Event) {
			$DivID++;

			if($DivID % 2 != 0) {
				$DivBG = '#F5F5F5';
			} else {
				$DivBG = '#EBEBEB';
			}

			if($Event['eventid'] == 1) $DivBG = '#FFCDCD';

			$EventHTML .= '<div style="background-color:'.$DivBG.'; border-bottom: solid 1px #CCC; padding:5px" title="['.date('d.m.Y H:i:s', $Event['timestamp']).']"><strong>'.$Event['title'].'</strong>'.(!is_null($Event['text'])?'<br />':null).$Event['text'];
			/*
			if($Event['pv'] != null) {
				$ide='debug_'.$this->rndStr();
				$EventHTML .= '<div class="panel-group" id="accordion">';
				$EventHTML .= ' <div class="panel panel-default">';
				$EventHTML .= ' <div class="panel-heading">';
				$EventHTML .= ' <h4 class="panel-title">';
				$EventHTML .= ' <a data-toggle="collapse" data-parent="#accordion" href="#'.$ide.'">full</a>';
				$EventHTML .= '</h4>';
				$EventHTML .= '</div>';
				$EventHTML .= '<div id="'.$ide.'" class="panel-collapse collapse">';
				$EventHTML .= ' <div class="panel-body">';
				foreach($Event['pv'] as $PV) {

					$EventHTML .= $PV.'<br/>';
				}
				$EventHTML .= '</div>';
				$EventHTML .= '</div>';
				$EventHTML .= '</div>';
				$EventHTML .= '</div>';
			}
			*/

			$EventHTML .= '</div>';
		}

		$FileContent = '
						<div style="margin: 0px; padding: 0px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px">
						<h4>Report '.$this->StartTime.'</h4>'.
						$EventHTML.
						'</div>

						';

		$FileContent = str_replace("\t", "", $FileContent);

		if($File != null) {
			$FileID = fopen($File, 'w');
			fwrite($FileID, $FileContent);
			fclose($FileID);
			//echo "<!-- Report saved into file ".$File." -->";
		} elseif($this->LogFolder != false) {
			if(substr($this->LogFolder, -1) != '/') {
				$FileName = $this->LogFolder.'/'.$this->StartTime.'_'.Session::get('UID').'.html';
			} else {
				$FileName = $this->LogFolder.$this->StartTime.'_'.Session::get('UID').'.html';
			}
			$uid=Session::get('UID');
			if(!empty($uid)){
				$FileID = fopen($FileName, 'w');
				fwrite($FileID, $FileContent);
				fclose($FileID);
		    }
			//echo "<!-- Report saved into file ".$FileName." -->";
		} else {
			//$this->OutputToPage();
		}
	}

	

	public function OutputToPage() {
		$EventHTML = "\n<!-- \n";

		foreach($this->Events as $Event) {
			$EventHTML .= "[".date('d.m.Y H:i:s', $Event['timestamp'])."] ".$Event['title'].(!is_null($Event['text'])?"\n":"").$Event['text']."\n\r";
		}

		$EventHTML .= "-->";

		echo $EventHTML;
	}

	public function End($OutputToFile = false) {
		$this->AddMessage('Script stop');
		if($this->WasErrors || $OutputToFile || $this->write) $this->OutputToFile(null,false);
	}

	 function rndStr($len = 10) {
	     $randomData = file_get_contents('/dev/urandom', false, null, 0, $len) . uniqid(mt_rand(), true);
	     $str = substr(str_replace(array('/','=','+'),'', base64_encode($randomData)),0,$len);
	    return $str;
     }
}
?>