<?php
$class = array('Manage','DbManage');
foreach($class as $clase){
  __autoload($clase);
}

use \Classes\Files\Manage as Manage;
use \Classes\Files\DbManage as DbManage;
$dbManage = new DbManage();
$manage = new Manage();


$html =

$manage->loadTemplate('header');
$manage->loadTemplatesWithAditionalHtml('list',$dbManage->getDataCsv());
$manage->loadTemplate('footer');


function __autoload($nombreClase) {

    $directorio = "classes/files/{$nombreClase}.php";

    if(file_exists($directorio)) {
        require_once($directorio);
    } else {
        die("El archivo {$nombreClase}.php no se ha podido encontrar.");
    }
}
