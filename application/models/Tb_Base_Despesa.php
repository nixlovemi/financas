<?php
class Tb_Base_Despesa extends CI_Model {
  public function getBaseDespesas(){
    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["arrBaseDespesas"] = array();

    $this->load->database();
    $this->db->select("bdp_id, bdp_descricao, bdp_tipo");
    $this->db->from("tb_base_despesa");
    $this->db->where("bdp_ativo", 1);
    $this->db->order_by("bdp_descricao", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrBaseDespesas = [];
        $arrBaseDespesas["bdp_id"]        = $rs1["bdp_id"];
        $arrBaseDespesas["bdp_descricao"] = $rs1["bdp_descricao"];
        $arrBaseDespesas["bdp_tipo"]      = $rs1["bdp_tipo"];

        $arrRet["arrBaseDespesas"][] = $arrBaseDespesas;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }
}
