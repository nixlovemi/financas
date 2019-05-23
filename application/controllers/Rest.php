<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {
  public function __construct(){
    CI_Controller::__construct();

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
      die();
    }
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

  // tb_lancamento =
  public function getLancamento($id){
    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restGetLancamento($id);

    echo json_encode($ret);
  }

  public function addLancamento(){
    extract($_POST);

    $despesa      = $despesa ?? null;
    $tipo         = $tipo ?? null;
    $parcela      = $parcela ?? null;
    $vencimento   = $vencimento ?? null;
    $valor        = $valor ?? null;
    $categoria    = $categoria ?? null;
    $pagamento    = $pagamento ?? null;
    $valor_pago   = $valor_pago ?? null;
    $conta        = $conta ?? null;
    $observacao   = $observacao ?? null;
    $confirmado   = $confirmado ?? 0;
    $repete_meses = $repeteMeses ?? null;

    $Lancamento = [];
    $Lancamento["lan_despesa"]    = $despesa;
    $Lancamento["lan_tipo"]       = $tipo;
    $Lancamento["lan_parcela"]    = $parcela;
    $Lancamento["lan_vencimento"] = $vencimento;
    $Lancamento["lan_valor"]      = $valor;
    $Lancamento["lan_categoria"]  = $categoria;
    $Lancamento["lan_pagamento"]  = $pagamento;
    $Lancamento["lan_valor_pago"] = $valor_pago;
    $Lancamento["lan_conta"]      = $conta;
    $Lancamento["lan_observacao"] = $observacao;
    $Lancamento["lan_confirmado"] = $confirmado;

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restAddLancamento($Lancamento, $repete_meses);

    echo json_encode($ret);
  }

  public function editLancamento(){
    extract($_POST);

    $id           = $id ?? null;
    $despesa      = $despesa ?? null;
    $tipo         = $tipo ?? null;
    $vencimento   = $vencimento ?? null;
    $valor        = $valor ?? null;
    $categoria    = $categoria ?? null;
    $pagamento    = $pagamento ?? null;
    $valor_pago   = $valor_pago ?? null;
    $conta        = $conta ?? null;
    $observacao   = $observacao ?? null;
    $confirmado   = $confirmado ?? 0;

    $Lancamento = [];
    $Lancamento["lan_id"]         = $id;
    $Lancamento["lan_despesa"]    = $despesa;
    $Lancamento["lan_tipo"]       = $tipo;
    $Lancamento["lan_vencimento"] = $vencimento;
    $Lancamento["lan_valor"]      = $valor;
    $Lancamento["lan_categoria"]  = $categoria;
    $Lancamento["lan_pagamento"]  = $pagamento;
    $Lancamento["lan_valor_pago"] = $valor_pago;
    $Lancamento["lan_conta"]      = $conta;
    $Lancamento["lan_observacao"] = $observacao;
    $Lancamento["lan_confirmado"] = $confirmado;

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restEditLancamento($Lancamento);

    echo json_encode($ret);
  }

  public function deleteLancamento($id){
    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restDeleteLancamento($id);

    echo json_encode($ret);
  }
  // ===============
}
