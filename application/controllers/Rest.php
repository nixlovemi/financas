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

    $this->load->helper("utils_helper");
  }

  // esquema do FCM Google
  public function fcmNotifContasPagar(){
    $hoje = date("Y-m-d");

    $this->load->model('Tb_Lancamento');
    $return = $this->Tb_Lancamento->restFcmNotifContasPagar($hoje);

    if($return !== false){
      // $to   = ["eBIf9IyhkVM:APA91bGpDMYZ2BwwqPkUFQS-aTTmZ4Z0s4s175GBLhAymwsTG3SYwqAjfMBnz6mEKRV0JKznEJV3Y6IJ2RgRWNzSve9fTl5qIdOQvfoTdzc24cUS2hv2h3Xp4h_PJD2jPEJov7-WC8QK"];
      $to   = "/topics/financasapp";
      $data = [
        "title" => "Contas a Vencer",
        "body"  => $return,
        "sound" => "default",
      ];
      $result = sendPushNotifications($to, $data);
      print_r($result);
    }
  }
  // =====================

  // tb_base_despesa
  public function getBaseDespesa(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restGetBaseDespesa($id);

    echo json_encode($ret);
  }

  public function addBaseDespesa(){
    $request = proccessPost();

    $descricao   = $request->descricao ?? "";
    $tipo        = $request->tipo ?? "";
    $contabiliza = $request->contabiliza ?? 1;
    $ativo       = $request->ativo ?? 1;

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
    $request = proccessPost();

    $id          = $request->id ?? "";
    $descricao   = $request->descricao ?? "";
    $tipo        = $request->tipo ?? "";
    $contabiliza = $request->contabiliza ?? 1;
    $ativo       = $request->ativo ?? 1;

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

  public function deleteBaseDespesa(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->restDeleteBaseDespesa($id);

    echo json_encode($ret);
  }

  public function getBaseDespesas(){
    $request = proccessPost();

    $this->load->model('Tb_Base_Despesa');
    $ret = $this->Tb_Base_Despesa->getBaseDespesas();

    echo json_encode($ret);
  }
  // ===============

  // tb_conta ======
  public function getConta(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restGetConta($id);

    echo json_encode($ret);
  }

  public function addConta(){
    $request = proccessPost();

    $nome          = $request->nome ?? "";
    $sigla         = $request->sigla ?? "";
    $data_saldo    = $request->data_saldo ?? "";
    $saldo_inicial = $request->saldo_inicial ?? 0;
    $ativo         = $request->ativo ?? 1;

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
    $request = proccessPost();

    $id            = $request->id ?? "";
    $nome          = $request->nome ?? "";
    $sigla         = $request->sigla ?? "";
    $data_saldo    = $request->data_saldo ?? "";
    $saldo_inicial = $request->saldo_inicial ?? 0;
    $ativo         = $request->ativo ?? 1;

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

  public function deleteConta(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->restDeleteConta($id);

    echo json_encode($ret);
  }

  public function getContas(){
    $request = proccessPost();

    $this->load->model('Tb_Conta');
    $arrRet = $this->Tb_Conta->getContas();

    echo json_encode($arrRet);
  }

  public function getSaldoContas()
  {
    $request = proccessPost();
    $mes     = $request->mes;
    $ano     = $request->ano;

    $this->load->model('Tb_Conta');
    $ret = $this->Tb_Conta->getHtmlSaldoContas($mes, $ano, true);
    
    echo json_encode($ret);
  }
  // ===============

  // tb_lancamento =
  public function getLancamento(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restGetLancamento($id);

    echo json_encode($ret);
  }

  public function addLancamento(){
    $request = proccessPost();

    $despesa      = $request->despesa ?? null;
    $tipo         = $request->tipo ?? null;
    $parcela      = $request->parcela ?? null;
    $vencimento   = $request->vencimento ?? null;
    $valor        = $request->valor ?? null;
    $categoria    = $request->categoria ?? null;
    $pagamento    = $request->pagamento ?? null;
    $valor_pago   = $request->valor_pago ?? null;
    $conta        = $request->conta ?? null;
    $observacao   = $request->observacao ?? null;
    $confirmado   = $request->confirmado ?? 0;
    $repete_meses = $request->repeteMeses ?? null;

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
    $request = proccessPost();

    $id           = $request->id ?? null;
    $despesa      = $request->despesa ?? null;
    $tipo         = $request->tipo ?? null;
    $vencimento   = $request->vencimento ?? null;
    $valor        = $request->valor ?? null;
    $categoria    = $request->categoria ?? null;
    $pagamento    = $request->pagamento ?? null;
    $valor_pago   = $request->valor_pago ?? null;
    $conta        = $request->conta ?? null;
    $observacao   = $request->observacao ?? null;
    $confirmado   = $request->confirmado ?? 0;

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

  public function deleteLancamento(){
    $request = proccessPost();
    $id      = $request->id;

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->restDeleteLancamento($id);

    echo json_encode($ret);
  }

  public function getLancamentos(){
    $request = proccessPost();

    $arrFilters = [];
    $arrFilters["mesBase"]     = $request->mesBase ?? "";
    $arrFilters["anoBase"]     = $request->anoBase ?? "";
    $arrFilters["vctoIni"]     = $request->vctoIni ?? "";
    $arrFilters["vctoFim"]     = $request->vctoFim ?? "";
    $arrFilters["pgtoIni"]     = $request->pgtoIni ?? "";
    $arrFilters["pgtoFim"]     = $request->pgtoFim ?? "";
    $arrFilters["descricao"]   = $request->descricao ?? "";
    $arrFilters["conta"]       = $request->conta ?? "";
    $arrFilters["tipo"]        = $request->tipo ?? "";
    $arrFilters["categoria"]   = $request->categoria ?? "";
    $arrFilters["apenasPagas"] = $request->apenasPagas ?? "";
    $arrFilters["limit"]       = $request->limit ?? "";
    $arrFilters["offset"]      = $request->offset ?? "";

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->getHtmlLancamentos($arrFilters, true, true);

    echo json_encode($ret);
  }

  public function getCategoriaGastos(){
    $request = proccessPost();
    $mes     = $request->mes;
    $ano     = $request->ano;

    $this->load->model('Tb_Lancamento');
    $ret = $this->Tb_Lancamento->getArrCategoriaGastos($mes, $ano);

    echo json_encode($ret);
  }
  // ===============

  // tb_usuario ====
  public function checkLogin(){
    $request  = proccessPost();

    $usuario  = $request->user ?? "";
    $senha    = $request->password ?? "";

    $this->load->model('Tb_Usuario');
    $arrInfo             = [];
    $arrInfo["user"]     = $usuario;
    $arrInfo["password"] = $senha;
    $arrRet              = $this->Tb_Usuario->checkLogin($arrInfo);

    echo json_encode($arrRet);
  }
  // ===============
}
