<?php 
    /** 
     * This class is here to help you create cron jobs with ease. 
     * 
     */ 
    defined('_XSCMS_PFQ_ADM_LIB') or die('ERROR');
    class crontab{ 
        
        var $minute=NULL; 
        var $hour=NULL; 
        var $day=NULL; 
        var $month=NULL; 
        var $dayofweek=NULL; 
        var $command=NULL; 
        var $directory=NULL; 
        var $filename="crons"; 
        var $crontabPath=NULL; 
        var $handle=NULL; 
        var $errors=array(); 
        public $title; 
        public $server = ""; 
        public $user = ""; 
        public $pass = ""; 
        protected static $db=null;
        
        
        /** 
         * Constructor. Attempts to create directory for 
         * holding cron jobs 
         * 
         * @param string $dir Directory to hold cron job files 
         * @param string $filename Filename to write to 
         * @param string $crontabPath Path to cron program 
         * @access public 
         */ 
        function crontab($dir=NULL, $filename=NULL, $modewrite='a', $crontabPath=NULL ){ 
            $result =(!$dir) ? $this->setDirectory("~/my_crontabs") : $this->setDirectory($dir); 
            if(!$result){

                    mkdir($dir); 
                    chmod($dir, 0775); 
                    $result =(!$dir) ? $this->setDirectory("~/my_crontabs") : $this->setDirectory($dir); 
            } 
                
            $result =(!$filename) ? $this->createCronFile("crons",$modewrite) : $this->createCronFile($filename,$modewrite); 
            if(!$result) 
                exit('File error'); 
            $this->pathToCrontab=($crontabPath) ? NULL : $crontabPath; 
        } 


        function setDateParams($min=NULL, $hour=NULL, $day=NULL, $month=NULL, $dayofweek=NULL){ 
            
            if($min=="0") 
                $this->minute=0; 
            elseif($min) 
                $this->minute=$min; 
            else 
                $this->minute="*"; 
            
            if($hour=="0") 
                $this->hour=0; 
            elseif($hour) 
                $this->hour=$hour; 
            else 
                $this->hour="*"; 
            $this->month=($month) ? $month : "*"; 
            $this->day=($day) ? $day : "*"; 
            $this->dayofweek=($dayofweek) ? $dayofweek : "*"; 
            
        } 
        

        function setDirectory($directory){ 
            if(!$directory) return false; 
            
            if(is_dir($directory)){ 
                if($dh=opendir($directory)){ 
                    $this->directory=$directory; 
                    return true; 
                }else 
                    return false; 
            }else{ 
                if(mkdir($directory, 0700)){ 
                    $this->directory=$directory; 
                    return true; 
                } 
            } 
            return false; 
        } 
        

        function createCronFile($filename=NULL,$modewrite){ 
            if(!$filename) 
                return false; 
            
            if(file_exists($this->directory.$filename)){ 
                if($handle=fopen($this->directory.$filename, $modewrite)){ 
                    $this->handle=&$handle; 
                    $this->filename=$filename; 
                    return true; 
                }else 
                    return false; 
            } 
            
            if(!$handle=fopen($this->directory.$filename, $modewrite)) 
                return false; 
            else{ 
                $this->handle=&$handle; 
                $this->filename=$filename; 
                return true; 
            } 
        } 
        

        function setCommand($command){ 
            if($command){ 
                $this->command=$command; 
                return false; 
            }else 
                return false; 
        } 
        

        function saveCronFile(){ 
            $command=$this->minute." ".$this->hour." ".$this->day." ".$this->month." ".$this->dayofweek." ".$this->command."\n"; 
            if(!fwrite($this->handle, $command)) 
                return true; 
            else 
                return false; 
        } 
        

        function addToCrontab(){ 
            
            if(!$this->filename) 
                exit('No name specified for cron file'); 
                        
            if(exec($this->pathToCrontab."crontab ".$this->directory.$this->filename)){ 
                return true; 
            }else{ 
                $this->errors[] = mysql_error(); 
                return false;} 
        } 
        

        public function showerror(){ 
            print_r($this->errors) or var_dump($this->errors); 
        } 
        

        function destroyFilePoint(){ 
            fclose($this->handle); 
            return true; 
        } 
        
    
        function clearParameters(){ 
            $this->command = ""; 
            $this->minute=0; 
            $this->hour=0; 
            $this->month="*"; 
            $this->day="*"; 
            $this->dayofweek="*"; 
            return true; 
        } 
        
  
        public function savetodb(){ 
            $query = "INSERT INTO crontab (command,title,minute,hour,day,month,dayofweek) VALUES ('{$this->command}','{$this->title}','{$this->minute}','{$this->hour}','{$this->day}','{$this->month}','{$this->dayofweek}')";
           if ($result = self::$db->query($query)){
                return true; 
            }else{ 
                return false; 
            } 
        } 
        

        public function retrievedb(){ 

             $query = 'select * from '.'crontab';
               if ($result = self::$db->query($query)){

                 while($row = $result->fetch_array()){ 
                   $a++; 
                   $daterd[$a]["minute"]=$row[minute]; 
                   $daterd[$a]["hour"]=$row[hour]; 
                   $daterd[$a]["day"]=$row[day]; 
                   $daterd[$a]["month"]=$row[month]; 
                   $daterd[$a]["dayofweek"]=$row[dayofweek]; 
                   $daterd[$a]["command"]=$row[command]; 
                   $daterd[$a]["title"]=$row[title]; 
                 } 

               }else{die("error ".mysql_error());}
            
            return $daterd; 
        } 
        

        public function setDBObject($db){
            self::$db=$db;
            if (!self::$db){
             Controller::returnMessage("Can't connect to MySQL: " .mysqli_connect_error(), "Danger", "OPS");
             exit;
        }

        }

        function saveCronFilebydb(){ 

               $commandw = ""; 

               $query = 'select * from '.'crontab';
               if ($result = self::$db->query($query)){

                 while($row = $result->fetch_array()){ 
                   $commandw.=$row["minute"]." ".$row["hour"]." ".$row["day"]." ".$row["month"]." ".$row["dayofweek"]." ".$row["command"]."\n";
                 } 

               }else{die("error ".mysql_error());}

            if(!fwrite($this->handle, $commandw)) 
                return true; 
            else 
                return false; 
       } 
       
    } 
        
?> 