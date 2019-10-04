<?php
namespace Classes\Files;

use Classes\Files\Encrypt as Encrypt;

class DbManage {

  const path = 'pub/accounts.csv';

  const pathTemporay = 'pub/accountsTemp.csv';

  const pathLog = 'pub/log.csv';

  const subfijo = 'NE';

  public static function createDataBase($data){
    try{
      return DbManage::createDb($data);
    }catch(\Exception $e){
      return array('status' => 'KO', 'message' => $e->getMessage());
    }
  }

  function log($data){

    $id       = $data['id'];
    $fecha    = date('Y-m-d h:i:s');
    $campo    = $data['campo'];
    $original = $data['original'];
    $nuevo    = $data['nuevo'];

    if(!file_exists(self::pathLog)){

      $data = array("Id|Fecha|Campo|Dato Original|Nuevo Dato",
      "$id|$fecha|$campo|$original|$nuevo");

      $fp = fopen(self::pathLog, 'w');
      foreach($data as $line){
               $val = explode("|",$line);
               fputcsv($fp, $val);
      }
    }else{

      $fp = fopen(self::pathLog, 'a');
      $data = "$id|$fecha|$campo|$original|$nuevo";

      $fp = fopen(self::pathLog, 'a');
      $val = explode("|",$data);
      fputcsv($fp, $val);
    }
      fclose($fp);
  }
  /*
  *Replace Data in csv
  * @param data mixer
  */
  function replaceDataInCsv($dataIn){

    $input = fopen(self::path, 'r');
    $output = fopen(self::pathTemporay, 'w');
    while( false !== ( $data = fgetcsv($input) ) ){

       if ($data[1] == $dataIn['rowId']) {

          if($data[2] != $dataIn['name']){
            DbManage::log(array(
              'id'    => $dataIn['rowId'],
              'campo' => 'nombre',
              'original'  => $data[2],
              'nuevo' => $dataIn['name']
            ));
            $data[2] = $dataIn['name'];
          }
          if($data[3] != $dataIn['address']){
            DbManage::log(array(
              'id'    => $dataIn['rowId'],
              'campo' => 'dirección',
              'original'  => $data[3],
              'nuevo' => $dataIn['address']
            ));
            $data[3] = $dataIn['address'];
          }
          if($data[4] != $dataIn['phone']){
            DbManage::log(array(
              'id'    => $dataIn['rowId'],
              'campo' => 'teléfono',
              'original'  => $data[4],
              'nuevo' => $dataIn['phone']
            ));
            $data[4] = $dataIn['phone'];
          }

       }

       fputcsv( $output, $data);
    }

    //close both files
    fclose($input);
    fclose($output);

    //clean up
    unlink(self::path);
    rename(self::pathTemporay,self::path);

    return array('status' => 'OK');

  }

    /*
    * Load Data inCSV with params
    * @param id string
    */
    function getDataInLog($id){

      $row = 1;
      $out = '';
      if (($gestor = fopen(self::pathLog, 'r')) !== FALSE) {
          while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            if($row > 1 && strlen($datos[2]) > 0){
              if($id == $datos[0]){
                $out .= '<tr>
                    <td>'.$datos[1].'</td>
                    <td>'.$datos[2].'</td>
                    <td>'.$datos[3].'</td>
                    <td>'.$datos[4].'</td>
                  </tr>';
              }
            }
              ++$row;
          }
          fclose($gestor);
      }
      return $out;
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
