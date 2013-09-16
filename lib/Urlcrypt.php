<?php

class Urlcrypt {
  static $table = "1bcd2fgh3jklmn4pqrstAvwxyz567890";
  static $key = "";
  static $cipher = MCRYPT_RIJNDAEL_128;
  static $mode = MCRYPT_MODE_CBC;

  public static function encode($str) {

    $n = strlen($str) * 8 / 5;
    $arr = str_split($str, 1);

    $m = "";
    foreach($arr as $c)$m .= str_pad(decbin(ord($c)), 8, "0", STR_PAD_LEFT);

    $p = ceil(strlen($m)/5)*5;

    $m = str_pad($m, $p, "0", STR_PAD_RIGHT);

    $newstr = "";
    for($i=0;$i<$n;$i++)$newstr .= Urlcrypt::$table[bindec(substr($m, $i*5, 5))]; 

    return $newstr;
  }

  public static function decode($str) {
    $n = strlen($str) * 5 / 8;
      $arr = str_split($str, 1);

      $m = "";
      foreach($arr as $c)$m .= str_pad(decbin(array_search($c, str_split(Urlcrypt::$table))), 5, "0", STR_PAD_LEFT); 

      $oldstr = "";
      for($i=0;$i<floor($n);$i++) $oldstr .= chr(bindec(substr($m, $i*8,8)));

      return $oldstr;
  }

  public static function encrypt($str){
    if(Urlcrypt::$key === "") throw new Exception('No key provided.');
    $key = pack('H*', Urlcrypt::$key);

    $iv_size = mcrypt_get_iv_size(Urlcrypt::$cipher, Urlcrypt::$mode);

    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $str = utf8_encode($str);

    $ciphertext = mcrypt_encrypt(Urlcrypt::$cipher, $key, $str, Urlcrypt::$mode, $iv);

    $ciphertext = $iv . $ciphertext;

    return Urlcrypt::encode($ciphertext);   
  }

  public static function decrypt($str){
    if(Urlcrypt::$key === "") throw new Exception('No key provided.');
    $key = pack('H*', Urlcrypt::$key);


    $str = Urlcrypt::decode($str);

    $iv_size = mcrypt_get_iv_size(Urlcrypt::$cipher, Urlcrypt::$mode);
    $iv_dec = substr($str, 0, $iv_size);

    $str = substr($str, $iv_size);

    $str = mcrypt_decrypt(Urlcrypt::$cipher, $key, $str, Urlcrypt::$mode, $iv_dec);

    // http://jonathonhill.net/2013-04-05/write-tests-you-might-learn-somethin/
    return rtrim($str, "\0");;
  }
}

Urlcrypt::$table = str_split(Urlcrypt::$table, 1);
