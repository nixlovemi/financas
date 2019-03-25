<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {
  public function __construct(){
    CI_Controller::__construct();
  }

  // tb_base_despesa
  public function getBaseDespesa($id){
    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restGetBaseDespesa($id);

    echo json_encode($ret);
  }

  public function addBaseDespesa(){
    extract($_POST);

    $descricao   = $descricao ?? "";
    $tipo        = $tipo ?? "";
    $contabiliza = $contabiliza ?? 1;
    $ativo       = $ativo ?? 1;

    $BaseDespesa = [];
    $BaseDespesa["bdp_descricao"]   = $descricao;
    $BaseDespesa["bdp_tipo"]        = $tipo;
    $BaseDespesa["bdp_contabiliza"] = $contabiliza;
    $BaseDespesa["bdp_ativo"]       = $ativo;

    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restAddBaseDespesa($BaseDespesa);

    echo json_encode($ret);
  }

  public function editBaseDespesa(){
    extract($_POST);

    $id          = $id ?? "";
    $descricao   = $descricao ?? "";
    $tipo        = $tipo ?? "";
    $contabiliza = $contabiliza ?? 1;
    $ativo       = $ativo ?? 1;

    $BaseDespesa = [];
    $BaseDespesa["bdp_id"]          = $id;
    $BaseDespesa["bdp_descricao"]   = $descricao;
    $BaseDespesa["bdp_tipo"]        = $tipo;
    $BaseDespesa["bdp_contabiliza"] = $contabiliza;
    $BaseDespesa["bdp_ativo"]       = $ativo;

    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restEditBaseDespesa($BaseDespesa);

    echo json_encode($ret);
  }

  public function deleteBaseDespesa($id){
    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restDeleteBaseDespesa($id);

    echo json_encode($ret);
  }
  // ===============
}
