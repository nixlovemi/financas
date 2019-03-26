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

  private function validaInsert($arrBaseDespesa){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $strDescricao = $arrBaseDespesa["bdp_descricao"] ?? "";
    if(strlen($strDescricao) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma descrição de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $strTipo = $arrBaseDespesa["bdp_tipo"] ?? "";
    if($strTipo != "I" && $strTipo != "F" && $strTipo != "V"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um tipo válido! Opções: I, F, V.";

      return $arrRet;
    }

    $strContabiliza = $arrBaseDespesa["bdp_contabiliza"] ?? "";
    if($strContabiliza != "0" && $strContabiliza != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de contabiliza válida! Opções: 0, 1.";

      return $arrRet;
    }

    $strAtivo = $arrBaseDespesa["bdp_ativo"] ?? "";
    if($strContabiliza != "0" && $strContabiliza != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de ativo válida! Opções: 0, 1.";

      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrBaseDespesa){
    $arrRet                = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["BaseDespesa"] = "";

    $retValidacao = $this->validaInsert($arrBaseDespesa);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->helpers("utils");

    $vDescricao   = isset($arrBaseDespesa["bdp_descricao"]) ? $arrBaseDespesa["bdp_descricao"]: "";
    $vTipo        = isset($arrBaseDespesa["bdp_tipo"]) ? $arrBaseDespesa["bdp_tipo"]: "V";
    $vContabiliza = isset($arrBaseDespesa["bdp_contabiliza"]) ? $arrBaseDespesa["bdp_contabiliza"]: 1;
    $vAtivo       = isset($arrBaseDespesa["bdp_ativo"]) ? $arrBaseDespesa["bdp_ativo"]: 1;

    $data = array(
      'bdp_descricao'   => $vDescricao,
      'bdp_tipo'        => $vTipo,
      'bdp_contabiliza' => $vContabiliza,
      'bdp_ativo'       => $vAtivo,
    );

    $this->db->trans_start();
    $this->db->insert('tb_base_despesa', $data);
    $bdbId = $this->db->insert_id();
    $this->db->trans_complete();
    $retInsert = $this->db->trans_status();

    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $retGBD = $this->restGetBaseDespesa($bdbId);

      $arrRet["BaseDespesa"] = ($retGBD["erro"] == true) ? array(): $retGBD["BaseDespesas"];
      $arrRet["erro"]        = false;
      $arrRet["msg"]         = "Base Despesa inserida com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrBaseDespesa){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $strId = $arrBaseDespesa["bdp_id"] ?? "";
    if(!is_numeric($strId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um ID válido para edição!";

      return $arrRet;
    }

    $strDescricao = $arrBaseDespesa["bdp_descricao"] ?? "";
    if(strlen($strDescricao) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma descrição de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $strTipo = $arrBaseDespesa["bdp_tipo"] ?? "";
    if($strTipo != "I" && $strTipo != "F" && $strTipo != "V"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um tipo válido! Opções: I, F, V.";

      return $arrRet;
    }

    $strContabiliza = $arrBaseDespesa["bdp_contabiliza"] ?? "";
    if($strContabiliza != "0" && $strContabiliza != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de contabiliza válida! Opções: 0, 1.";

      return $arrRet;
    }

    $strAtivo = $arrBaseDespesa["bdp_ativo"] ?? "";
    if($strContabiliza != "0" && $strContabiliza != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de ativo válida! Opções: 0, 1.";

      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrBaseDespesa){
    $arrRet                = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["BaseDespesa"] = "";

    $retValidacao = $this->validaEdit($arrBaseDespesa);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->helpers("utils");

    $vId          = isset($arrBaseDespesa["bdp_id"]) ? $arrBaseDespesa["bdp_id"]: "";
    $vDescricao   = isset($arrBaseDespesa["bdp_descricao"]) ? $arrBaseDespesa["bdp_descricao"]: "";
    $vTipo        = isset($arrBaseDespesa["bdp_tipo"]) ? $arrBaseDespesa["bdp_tipo"]: "V";
    $vContabiliza = isset($arrBaseDespesa["bdp_contabiliza"]) ? $arrBaseDespesa["bdp_contabiliza"]: 1;
    $vAtivo       = isset($arrBaseDespesa["bdp_ativo"]) ? $arrBaseDespesa["bdp_ativo"]: 1;

    $data = array(
      'bdp_descricao'   => $vDescricao,
      'bdp_tipo'        => $vTipo,
      'bdp_contabiliza' => $vContabiliza,
      'bdp_ativo'       => $vAtivo,
    );

    $this->db->trans_start();
    $this->db->where('bdp_id', $vId);
    $retInsert = $this->db->update('tb_base_despesa', $data);
    $this->db->trans_complete();
    $retInsert = $this->db->trans_status();

    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $retGBD = $this->restGetBaseDespesa($vId);

      $arrRet["BaseDespesa"] = ($retGBD["erro"] == true) ? array(): $retGBD["BaseDespesas"];
      $arrRet["erro"]        = false;
      $arrRet["msg"]         = "Base Despesa editada com sucesso!";
    }

    return $arrRet;
  }

  public function delete($id){
    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    if(!is_numeric($id)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar Base Despesa";

      return $arrRet;
    }

    $this->load->database();
    $this->db->trans_start();
    $this->db->where('bdp_id', $id);
    $this->db->delete('tb_base_despesa');
    $this->db->trans_complete();
    $retDelete = $this->db->trans_status();

    if(!$retDelete){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Base Despesa deletada com sucesso!";
    }

    return $arrRet;
  }

  public function restGetBaseDespesa($id){
    $arrRet = [];
    $arrRet["erro"]         = true;
    $arrRet["msg"]          = "";
    $arrRet["BaseDespesas"] = array();

    if(!is_numeric($id)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar Base Despesa";

      return $arrRet;
    }

    #SELECT  FROM tb_base_despesa
    $this->load->database();
    $this->db->select("bdp_id, bdp_descricao, bdp_tipo, bdp_contabiliza, bdp_ativo");
    $this->db->from("tb_base_despesa");
    $this->db->where("bdp_id", $id);

    $query = $this->db->get();
    $row   = $query->row();

    if($query->num_rows() > 0 && isset($row)){
      $BaseDespesa = [];
      $BaseDespesa["id"]          = $row->bdp_id;
      $BaseDespesa["descricao"]   = $row->bdp_descricao;
      $BaseDespesa["tipo"]        = $row->bdp_tipo;
      $BaseDespesa["contabiliza"] = $row->bdp_contabiliza;
      $BaseDespesa["ativo"]       = $row->bdp_ativo;

      $arrRet["erro"]         = false;
      $arrRet["msg"]          = "Base Despesa encontrada com sucesso!";
      $arrRet["BaseDespesas"] = $BaseDespesa;

      return $arrRet;
    } else {
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Nenhuma Base Despesa encontrada!";

      return $arrRet;
    }
  }

  public function restAddBaseDespesa($BaseDespesa){
    return $this->insert($BaseDespesa);
  }

  public function restEditBaseDespesa($BaseDespesa){
    return $this->edit($BaseDespesa);
  }

  public function restDeleteBaseDespesa($id){
      $retBD  = $this->restGetBaseDespesa($id);
      $retDel = $this->delete($id);

      $retDel["BaseDespesas"] = ($retBD["erro"]) ? array(): $retBD["BaseDespesas"];

      return $retDel;
  }
}
