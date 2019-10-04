<?php
namespace Classes\Files;

use Classes\Files\Encrypt as Encrypt;

class DbManage {

  const path = 'pub/accounts.csv';

  const subfijo = 'NE';

  public static function createDataBase($data){
    try{
      return DbManage::createDb($data);
    }catch(\Exception $e){
      return array('status' => 'KO', 'message' => $e->getMessage());
    }
  }
  /*
  *Replace Data in csv
  * @param data mixer
  */
  function replaceDataInCsv($data){

    /*if (($gestor = fopen(self::path, 'w+')) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
          if ($data['rowId'] == $datos[1]) {
            $newLine = $data[0].'|'.$data[1].'|'.$data['name'].'|'.$data['address'].'|'.$data['phone'].'|';

              fputcsv($gestor, $newLine);
          }else{
            $newLine = $data[0].'|'.$data[1].'|'.$data[2].'|'.$data[3].'|'.$data[4].'|';

              fputcsv($gestor, $newLine);
          }
        }
      }
    fclose($gestor);*/

    return array('status' => 'OK');

  }

  /*
  * Load Data inCSV with params
  * @param id string
  */
  function getDataInCsv($id){

    $row = 1;
    $out = '';
    if (($gestor = fopen(self::path, 'r')) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
          if($row > 1 && strlen($datos[2]) > 0){
            if($id == $datos[1]){
              $out = '<form id="formUpdate">
                        <input type="hidden" id="rowId" value="'.$datos[1].'">
                        <input type="hidden" id="code" value="'.$datos[0].'">

                        <div class="form-group">
                          <label for="formName">Nombre</label>
                          <input type="text" pdatemaxlength="50" minlength="4" class="form-control" id="formName" aria-describedby="nameHelp" placeholder="Ingresa Nombre" required
                          value="'.$datos[2].'">
                          <small id="nameHelp" class="form-text text-muted">Ingresa tu nombre completo</small>
                        </div>
                        <div class="form-group">
                          <label for="formAddress">Dirección</label>
                          <input type="text" maxlength="50" minlength="10" class="form-control" id="formAddress" aria-describedby="addressHelp" placeholder="Ingresa Dirección" required
                          value="'.$datos[3].'">
                          <small id="addressHelp" class="form-text text-muted">Ingresa tu dirección</small>
                        </div>
                        <div class="form-group">
                          <label for="formPhone">Teléfono</label>
                          <input type="text" maxlength="10" minlength="10" class="form-control" id="formPhone" aria-describedby="phoneHelp" placeholder="Ingresa Número Telefonico" required
                          value="'.$datos[4].'">
                          <small id="phoneHelp" class="form-text text-muted">Ingresa tu número telefónico</small>
                        </div>
                        <button type="button" class="btn btn-success update-account"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>
                        <a href="list.php"><button type="button" class="btn btn-danger"><i class="fa fa-window-close-o" aria-hidden="true"></i> Cancelar</button></a>
                      </form>';
            }
          }
            ++$row;
        }
        fclose($gestor);
    }
    return $out;
  }
  /*
  * Load Data in CSV
  */
  function getDataCsv(){

    $row = 1;
    $out = '';
    if (($gestor = fopen(self::path, 'r')) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
          if($row > 1 && strlen($datos[2]) > 0){
            $out .= "<tr><td>".$datos[1]."</td><td>".$datos[2]."</td>
            <td><a href='editrecord.php?id=".$datos[1]."'>Editar</a></td></tr>";
          }
            ++$row;
        }
        fclose($gestor);
    }
    return $out;
  }
  /*
  * Crea un archivo interno csv a modo de base de datos
  * @param data mixer
  */
  function createDb($data){

    $dataName     = $data['name'];
    $dataAddress  = $data['address'];
    $dataPhone    = $data['phone'];
    $dataPhone    = str_replace(array(' ','-'),'',$dataPhone);
    $idExternal   = substr(trim($dataName),0,4).substr(trim($dataPhone),-4,trim(strlen($dataPhone)));
    $idExternal   = mb_strtoupper($idExternal);

      if(!file_exists(self::path)){

        $idExternal = self::subfijo.'100'.$idExternal;
        $encryptCode = Encrypt::cryptString(str_replace(self::subfijo,'',$idExternal));
        $data = array("CodeCrypt|Id|Nombre|Dirección|Telefono",
        "$encryptCode|$idExternal|$dataName|$dataAddress|$dataPhone");

        $fp = fopen(self::path, 'w');
        foreach($data as $line){
                 $val = explode("|",$line);
                 fputcsv($fp, $val);
        }

      }else{

        $cp = file(self::path, FILE_SKIP_EMPTY_LINES);
        $increment  = (int)100+(int)count($cp);
        $idExternal = self::subfijo.$increment.$idExternal;
        $encryptCode = Encrypt::cryptString(str_replace(self::subfijo,'',$idExternal));
        $fp = fopen(self::path, 'a');

        $data = "$encryptCode|$idExternal|$dataName|$dataAddress|$dataPhone";

        $fp = fopen(self::path, 'a');
        $val = explode("|",$data);
        fputcsv($fp, $val);
      }
      fclose($fp);

      return array('status' => 'OK', 'code' => $encryptCode, 'id' => $idExternal);
  }

}
