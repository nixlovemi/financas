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

  public function getConta($conId){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";
    $arrRet["arrContaDados"] = array();

    if(!is_numeric($conId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar a conta!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("con_id, con_nome, con_sigla, con_saldo_inicial, con_ativo");
    $this->db->from("tb_conta");
    $this->db->where("con_id", $conId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrContaDados = [];
      $arrContaDados["con_id"]            = $row->con_id;
      $arrContaDados["con_nome"]          = $row->con_nome;
      $arrContaDados["con_sigla"]         = $row->con_sigla;
      $arrContaDados["con_saldo_inicial"] = $row->con_saldo_inicial;
      $arrContaDados["con_ativo"]         = $row->con_ativo;

      $arrRet["arrContaDados"] = $arrContaDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }
}
