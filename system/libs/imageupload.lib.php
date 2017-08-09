<?php
class Thumbnail {
    var $errmsg       = "";
    var $error        = false;
    var $format       = "";
    var $file         = "";
    var $max_width    = 0;
    var $max_height   = 0;
    var $percent      = 0;
    var $jpeg_quality = 75;
    var $tmpFolder    = ""; 

    function Thumbnail(){ 
        $this->tmpFolder    = Registry::get("ImageTMP",null,true); 
        if (!is_dir($this->tmpFolder)) {
                    mkdir($this->tmpFolder); 
                    chmod($this->tmpFolder, 0600);        
            } 
    }
     
    function guid(){
    
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid =  substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4)
                .substr($charid,12, 4)
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
        
    }

     function setSize($max_width = 0, $max_height = 0){
       $this->max_width    = $max_width;
       $this->max_height   = $max_height;
     }

     function setQuality($jpeg_quality = 75){
       $this->jpeg_quality = $jpeg_quality;
     }

     function setPercent($percent = 0){
       $this->percent      = $percent;
     }

     function setFileData($file_data,$typeImg){
        $this->file = "./".$this->tmpFolder.$this->guid().".".$typeImg;
        file_put_contents($this->file, $file_data);


        if (!file_exists($this->file)) {
        $this->errmsg = "File doesn't exists";
        $this->error  = true;
        }
        else if (!is_readable($this->file)) {
            $this->errmsg = "File is not readable";
            $this->error  = true;
        }
        if (strstr(strtolower($this->file), ".gif")) {
            $this->format = "GIF";
        }
        else if (strstr(strtolower($this->file), ".jpg") ||
             strstr(strtolower($this->file), ".jpeg")) {
            $this->format = "JPEG";
        }
        else if (strstr(strtolower($this->file), ".png")) {
            $this->format = "PNG";
        }
        else {
            $this->errmsg = "Unknown file format";
            $this->error  = true;
        }
        if ($this->max_width == 0 && $this->max_height == 0 && $this->percent == 0) {
            $this->percent = 100;
        }
     }


    function calc_width($width, $height) {
    $new_width  = $this->max_width;
    $new_wp     = (100 * $new_width) / $width;
    $new_height = ($height * $new_wp) / 100;
    return array($new_width, $new_height);
    }
    function calc_height($width, $height) {
    $new_height = $this->max_height;
    $new_hp     = (100 * $new_height) / $height;
    $new_width  = ($width * $new_hp) / 100;
    return array($new_width, $new_height);
    }
    function calc_percent($width, $height) {
    $new_width  = ($width * $this->percent) / 100;
    $new_height = ($height * $this->percent) / 100;
    return array($new_width, $new_height);
    }
    function return_value($array) {
    $array[0] = intval($array[0]);
    $array[1] = intval($array[1]);
    return $array;
    }
    function calc_image_size($width, $height) {
    $new_size = array($width, $height);
    if ($this->max_width > 0 && $width > $this->max_width) {
        $new_size = $this->calc_width($width, $height);
        if ($this->max_height > 0 && $new_size[1] > $this->max_height) {
        $new_size = $this->calc_height($new_size[0], $new_size[1]);
        }
        return $this->return_value($new_size);
    }
    if ($this->max_height > 0 && $height > $this->max_height) {
        $new_size = $this->calc_height($width, $height);
        return $this->return_value($new_size);
    }
    if ($this->percent > 0) {
        $new_size = $this->calc_percent($width, $height);
        return $this->return_value($new_size);
    }
    return $this->return_value($new_size);
    }

    function show_error_image() {
        header("Content-type: image/png");
        $err_img   = ImageCreate(220, 25);
        $bg_color  = ImageColorAllocate($err_img, 0, 0, 0);
        $fg_color1 = ImageColorAllocate($err_img, 255, 255, 255);
        $fg_color2 = ImageColorAllocate($err_img, 255, 0, 0);
        ImageString($err_img, 3, 6, 6, "ERROR:", $fg_color2);
        ImageString($err_img, 3, 55, 6, $this->errmsg, $fg_color1);
        ImagePng($err_img);
        ImageDestroy($err_img);
    }

    function show($name = "") {
    if ($this->error) {
        $this->show_error_image();
        return;
    }
    $size      = GetImageSize($this->file);
    $new_size  = $this->calc_image_size($size[0], $size[1]);
    #
    # Good idea from Mariano Cano Pérez
    # Requires GD 2.0.1 (PHP >= 4.0.6)
    #
    if (function_exists("ImageCreateTrueColor")) {
        $new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
    }
    else {
        $new_image = ImageCreate($new_size[0], $new_size[1]);
    }
    switch ($this->format) {
        case "GIF":
        $old_image = ImageCreateFromGif($this->file);
        $alpha = imagecolortransparent($old_image);
        if($alpha >= 0){
          $trnprt_color    = imagecolorsforindex($old_image, $alpha);
          $trnprt_indx    = imagecolorallocate($new_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
          imagefill($new_image, 0, 0, $trnprt_indx);
          imagecolortransparent($new_image, $trnprt_indx);
        }
        break;
        case "JPEG":
        $old_image = ImageCreateFromJpeg($this->file);
        break;
        case "PNG":
        $old_image = ImageCreateFromPng($this->file);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        break;
    }
    #
    # Good idea from Michael Wald
    # Requires GD 2.0.1 (PHP >= 4.0.6)
    #
    if (function_exists("ImageCopyResampled")) {
        ImageCopyResampled($new_image, $old_image, 0, 0, 0, 0, $new_size[0], $new_size[1], $size[0], $size[1]);
    }
    else {
        ImageCopyResized($new_image, $old_image, 0, 0, 0, 0, $new_size[0], $new_size[1], $size[0], $size[1]);
    }
    switch ($this->format) {
        case "GIF":
        if (!empty($name)) {
            ImageGif($new_image, $name);
        }
        else {
            header("Content-type: image/gif");
            ImageGif($new_image);
        }
        break;
        case "JPEG":
        if (!empty($name)) {
            ImageJpeg($new_image, $name, $this->jpeg_quality);
        }
        else {
            header("Content-type: image/jpeg");
            ImageJpeg($new_image, "", $this->jpeg_quality);
        }
        break;
        case "PNG":
        if (!empty($name)) {
            ImagePng($new_image, $name);
        }
        else {
            header("Content-type: image/png");
            ImagePng($new_image);
        }
        break;
    }
    ImageDestroy($new_image);
    ImageDestroy($old_image);
    return;
    }
    function save($name) {
    $this->show($name);
    unlink($this->file);
    }
}
?>