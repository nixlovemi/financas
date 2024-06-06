<?php
class Database extends CI_Model {
  public function execScriptFile($scriptFile){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    if( !file_exists($scriptFile) ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Arquivo de script não encontrado!";

      return $arrRet;
    }

    // Temporary variable, used to store current query
    $templine = '';
    $this->load->database();
    $this->db->trans_start();

    $lines = file($scriptFile);

    foreach ($lines as $line){
      // Skip it if it's a comment
      if (substr($line, 0, 2) == '--' || $line == ''){
        continue;
      }

      // Add this line to the current segment
      $templine .= $line;

      // If it has a semicolon at the end, it's the end of the query
      if (substr(trim($line), -1, 1) == ';'){
        // Perform the query
        $query = $this->db->query($templine);
        if($query === false){
          $arrRet["erro"] = true;
          $arrRet["msg"]  = "Erro ao executar script!";

          return $arrRet;
        }

        // Reset temp variable to empty
        $templine = '';
      }
    }

    $this->db->trans_complete();

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "Script executado com sucesso!";
    return $arrRet;
  }
}
