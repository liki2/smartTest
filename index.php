<?php
$class = array('Manage');
foreach($class as $clase){
  __autoload($clase);
}

use \Classes\Files\Manage as Manage;
$manage = new Manage();

$manage->loadTemplate('header');
$manage->loadTemplate('cpannel');
$manage->loadTemplate('footer');


function __autoload($nombreClase) {

    $directorio = "classes/files/{$nombreClase}.php";

    if(file_exists($directorio)) {
        require_once($directorio);
    } else {
        die("El archivo {$nombreClase}.php no se ha podido encontrar.");
    }
}
