<?php
class Session {
      public static function init() {
        session_name("xscms");
        if (@session_id() == ""){ @session_start();  }     
      }



      public static function set($key, $value) {
       $_SESSION[$key] = $value;
      }

      public static function get($key) {
       if(isset($_SESSION[$key]))
       return $_SESSION[$key]; 
      }

      public static function destroy() {
        session_destroy();
        setcookie("UID", '', time()-3600);
        setcookie("USER", '', time()-3600);
        header("Location: /");
      }

         # Функция генерации случайной строки
      public static function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
      }

      # экранирование данных
      public static function screening($data) {
        $data = trim($data); //~ удаление пробелов из начала и конца строки
        return $data;
      }
}
?>