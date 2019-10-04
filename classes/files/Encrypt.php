<?php
namespace Classes\Files;

class Encrypt {

  /*
  * encrypta y secciona una cadena
  * @param string string
  */
  public static function cryptString($string){

    return substr(bin2hex($string),6,8);
  }

}
