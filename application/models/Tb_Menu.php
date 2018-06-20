<?php
class Tb_Menu extends CI_Model {
  public function getMenuArr(){
    $vMenuArr = [];

    $vSql  = " SELECT men_id, men_descricao, men_controller, men_action, men_vars, men_icon ";
    $vSql .= " FROM tb_menu ";
    $vSql .= " WHERE men_id_pai IS NULL ";
    $vSql .= " AND men_ativo = true ";
    $vSql .= " ORDER BY men_order, men_descricao ";

    $this->load->database();
    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    foreach($arrRs as $rs1){
      $vMenId1 = $rs1["men_id"];

      if(!array_key_exists($vMenId1, $vMenuArr)){
        $vMenDesc1  = $rs1["men_descricao"];
        $vMenContr1 = $rs1["men_controller"];
        $vMenActio1 = $rs1["men_action"];
        $vMenVars1  = $rs1["men_vars"];
        $vMenIcon1  = $rs1["men_icon"];

        $vMenuArr[$vMenId1]               = [];
        $vMenuArr[$vMenId1]["descricao"]  = $vMenDesc1;
        $vMenuArr[$vMenId1]["controller"] = $vMenContr1;
        $vMenuArr[$vMenId1]["action"]     = $vMenActio1;
        $vMenuArr[$vMenId1]["vars"]       = $vMenVars1;
        $vMenuArr[$vMenId1]["icon"]       = $vMenIcon1;
        $vMenuArr[$vMenId1]["child"]      = [];
      }

      $vSql2  = " SELECT men_id, men_descricao, men_controller, men_action, men_vars, men_icon ";
      $vSql2 .= " FROM tb_menu ";
      $vSql2 .= " WHERE men_id_pai = " . $vMenId1;
      $vSql2 .= " AND men_ativo = true ";
      $vSql2 .= " ORDER BY men_order, men_descricao ";

      $query2 = $this->db->query($vSql2);
      $arrRs2 = $query2->result_array();

      foreach($arrRs2 as $rs2){
        $vMenId2 = $rs2["men_id"];

        if(!array_key_exists($vMenId2, $vMenuArr[$vMenId1]["child"])){
          $vMenDesc2  = $rs2["men_descricao"];
          $vMenContr2 = $rs2["men_controller"];
          $vMenActio2 = $rs2["men_action"];
          $vMenVars2  = $rs2["men_vars"];
          $vMenIcon2  = $rs2["men_icon"];

          $vMenuArr[$vMenId1]["child"][$vMenId2] = [];
          $vMenuArr[$vMenId1]["child"][$vMenId2]["descricao"]  = $vMenDesc2;
          $vMenuArr[$vMenId1]["child"][$vMenId2]["controller"] = $vMenContr2;
          $vMenuArr[$vMenId1]["child"][$vMenId2]["action"]     = $vMenActio2;
          $vMenuArr[$vMenId1]["child"][$vMenId2]["vars"]       = $vMenVars2;
          $vMenuArr[$vMenId1]["child"][$vMenId2]["icon"]       = $vMenIcon2;
          $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]      = [];
        }

        $vSql3  = " SELECT men_id, men_descricao, men_controller, men_action, men_vars, men_icon ";
        $vSql3 .= " FROM tb_menu ";
        $vSql3 .= " WHERE men_id_pai = " . $vMenId2;
        $vSql3 .= " AND men_ativo = true ";
        $vSql3 .= " ORDER BY men_order, men_descricao ";

        $query3 = $this->db->query($vSql3);
        $arrRs3 = $query3->result_array();

        foreach($arrRs3 as $rs3){
          $vMenId3 = $rs3["men_id"];

          if(!array_key_exists($vMenId3, $vMenuArr[$vMenId1]["child"][$vMenId2]["child"])){
            $vMenDesc3  = $rs3["men_descricao"];
            $vMenContr3 = $rs3["men_controller"];
            $vMenActio3 = $rs3["men_action"];
            $vMenVars3  = $rs3["men_vars"];
            $vMenIcon3  = $rs3["men_icon"];

            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"][$vMenId3] = [];
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["descricao"]  = $vMenDesc3;
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["controller"] = $vMenContr3;
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["action"]     = $vMenActio3;
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["vars"]       = $vMenVars3;
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["icon"]       = $vMenIcon3;
            $vMenuArr[$vMenId1]["child"][$vMenId2]["child"]["child"]      = [];
          }
        }
      }
    }

    return $vMenuArr;
  }
}
