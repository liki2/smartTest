<?php
namespace Classes\Files;

class Manage {

  /*
  * Obtiene el contenido html y lo replica al index
  * @param template string
  */
  public static function loadTemplate($template){

    $página_inicio = file_get_contents('html/'.$template.'.html');
    echo $página_inicio;
  }
  /*
  * Obtiene el contenido html con valores a setear y lo replica al control
  * @param template string
  * @param html string
  */
  public static function loadTemplatesWithAditionalHtml($template,$html){

    $página_inicio = file_get_contents('html/'.$template.'.html');
    echo str_replace('{{html}}',$html,$página_inicio);
  }


}
