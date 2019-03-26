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

  // con_id, con_nome, con_sigla, con_data_saldo, con_saldo_inicial, con_ativo
  // tb_conta ======
  public function getConta($id){
    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restGetConta($id);

    echo json_encode($ret);
  }

  public function addConta(){
    extract($_POST);

    $nome          = $nome ?? "";
    $sigla         = $sigla ?? "";
    $data_saldo    = $data_saldo ?? "";
    $saldo_inicial = $saldo_inicial ?? 0;
    $ativo         = $ativo ?? 1;

    $Conta = [];
    $Conta["con_nome"]          = $nome;
    $Conta["con_sigla"]         = $sigla;
    $Conta["con_data_saldo"]    = $data_saldo;
    $Conta["con_saldo_inicial"] = $saldo_inicial;
    $Conta["con_ativo"]         = $ativo;

    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restAddConta($Conta);

    echo json_encode($ret);
  }

  public function editConta(){
    extract($_POST);

    $id            = $id ?? "";
    $nome          = $nome ?? "";
    $sigla         = $sigla ?? "";
    $data_saldo    = $data_saldo ?? "";
    $saldo_inicial = $saldo_inicial ?? 0;
    $ativo         = $ativo ?? 1;

    $Conta = [];
    $Conta["con_id"]            = $id;
    $Conta["con_nome"]          = $nome;
    $Conta["con_sigla"]         = $sigla;
    $Conta["con_data_saldo"]    = $data_saldo;
    $Conta["con_saldo_inicial"] = $saldo_inicial;
    $Conta["con_ativo"]         = $ativo;

    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restEditConta($Conta);

    echo json_encode($ret);
  }

  public function deleteConta($id){
    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restDeleteConta($id);

    echo json_encode($ret);
  }
  // ===============
}
