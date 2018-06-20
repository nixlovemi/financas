<?php
class Tb_Usuario extends CI_Model {
  /**
  * Check
  */
  public function checkLogin($arrInfo){
    $vUser     = isset($arrInfo["user"]) ? $arrInfo["user"]: "";
    $vPassword = isset($arrInfo["password"]) ? $arrInfo["password"]: "";

    $this->load->database();

    $vUserEsc     = $this->db->escape_str($vUser);
    $vPasswordEsc = $this->db->escape_str(md5($vPassword));

    $sql  = " SELECT * ";
    $sql .= " FROM tb_usuario ";
    $sql .= " WHERE usu_login = '$vUserEsc' ";
    $sql .= " AND usu_senha = '$vPasswordEsc' ";

    $query = $this->db->query($sql);
    $arrRs = $query->result_array();

    $arrReturn              = [];
    $arrReturn["error"]     = count($arrRs) <= 0;
    $arrReturn["usu_id"]    = (isset($arrRs[0]["usu_id"])) ? $arrRs[0]["usu_id"]: "";
    $arrReturn["usu_nome"]  = (isset($arrRs[0]["usu_nome"])) ? $arrRs[0]["usu_nome"]: "";
    $arrReturn["usu_email"] = (isset($arrRs[0]["usu_email"])) ? $arrRs[0]["usu_email"]: "";
    $arrReturn["usu_ativo"] = (isset($arrRs[0]["usu_ativo"])) ? $arrRs[0]["usu_ativo"]: "";

    return $arrReturn;
  }
}
