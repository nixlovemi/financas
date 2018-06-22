<?php
class Tb_Conta extends CI_Model {
  public function getContas(){
    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["arrContas"] = array();

    $this->load->database();
    $this->db->select("con_id, con_nome, con_sigla");
    $this->db->from("tb_conta");
    $this->db->where("con_ativo", 1);
    $this->db->order_by("con_nome", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrContas = [];
        $arrContas["con_id"]    = $rs1["con_id"];
        $arrContas["con_nome"]  = $rs1["con_nome"];
        $arrContas["con_sigla"] = $rs1["con_sigla"];

        $arrRet["arrContas"][] = $arrContas;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }
}
