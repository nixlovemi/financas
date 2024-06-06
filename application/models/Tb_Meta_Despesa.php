<?php
class Tb_Meta_Despesa extends CI_Model {
  public function insertArray($mes, $ano, $arrInfo){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $arrMeses = [1,2,3,4,5,6,7,8,9,10,11,12];
    if(!in_array($mes, $arrMeses)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Mês inválido para inserir!";
      return $arrRet;
    }

    if(!is_numeric($ano) || strlen($ano) <> 4){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Ano inválido para inserir!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->trans_start();

    foreach($arrInfo as $bdpId => $metaVlr){
      $vSqlCheck = "
        SELECT mdp_id
        FROM tb_meta_despesa
        WHERE mdp_mes = $mes
        AND mdp_ano = $ano
        AND mdp_despesa = $bdpId
      ";
      $query = $this->db->query($vSqlCheck);

      $row = $query->row();
      if (isset($row)){
        $mdpId = $row->mdp_id;

        $vSql = "
          UPDATE tb_meta_despesa
          SET mdp_valor = $metaVlr
          WHERE mdp_id = $mdpId
        ";
      } else {
        $vSql = "
          INSERT INTO tb_meta_despesa(mdp_despesa, mdp_mes, mdp_ano, mdp_valor)
          VALUES($bdpId, $mes, $ano, $metaVlr)
        ";
      }

      $this->db->query($vSql);
    }

    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao salvar as previsões!";
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Previsões salvas com sucesso!";
    }

    return $arrRet;
  }
}
