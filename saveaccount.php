<?php
$class = array('DbManage','Encrypt');
foreach($class as $clase){
  __autoload($clase);
}

use \Classes\Files\DbManage as DbManage;
$manage = new DbManage();

if(isset($_REQUEST['rowId'])){
  $out = $manage->replaceDataInCsv($_REQUEST);
}else{
  $out = $manage->createDataBase($_REQUEST);
}
echo json_encode($out,true);

function __autoload($nombreClase) {

    $directorio = "classes/files/{$nombreClase}.php";

    if(file_exists($directorio)) {
        require_once($directorio);
    } else {
        die("El archivo {$nombreClase}.php no se ha podido encontrar.");
    }
}
