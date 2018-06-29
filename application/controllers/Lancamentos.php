<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lancamentos extends MY_Controller {
  public function __construct(){
    MY_Controller::__construct();
  }

  public function index(){
    $data = [];

    $this->load->model('Tb_Lancamento');
    $htmlLancamentos         = $this->Tb_Lancamento->getHtmlLancamentos();
    $data["htmlLancamentos"] = $htmlLancamentos;

    $htmlTotaisGastos         = $this->Tb_Lancamento->getHtmlTotaisGastos();
    $data["htmlTotaisGastos"] = $htmlTotaisGastos;

    $this->load->model('Tb_Conta');
    $retContas = $this->Tb_Conta->getContas();
    $arrContas = ($retContas["erro"] == false) ? $retContas["arrContas"]: array();
    $data["arrContas"] = $arrContas;

    $this->load->model('Tb_Base_Despesa');
    $retBaseDesp = $this->Tb_Base_Despesa->getBaseDespesas();
    $arrBaseDesp = ($retBaseDesp["erro"] == false) ? $retBaseDesp["arrBaseDespesas"]: array();
    $data["arrBaseDesp"] = $arrBaseDesp;

    $this->template->load('template', 'Lancamentos/index', $data);
  }

  public function jsonHtmlAddLancamento(){
    $data = [];

    $this->load->model('Tb_Base_Despesa');
    $retBaseDesp = $this->Tb_Base_Despesa->getBaseDespesas();
    $arrBaseDesp = (!$retBaseDesp["erro"]) ? $retBaseDesp["arrBaseDespesas"]: array();
    $data["arrBaseDesp"] = $arrBaseDesp;

    $this->load->model('Tb_Conta');
    $retConta = $this->Tb_Conta->getContas();
    $arrConta = (!$retConta["erro"]) ? $retConta["arrContas"]: array();
    $data["arrConta"] = $arrConta;

    $htmlView = $this->load->view('Lancamentos/novo', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonAddLancamento(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $lanDespesa    = ($this->input->post('lanDespesa') != "") ? $this->input->post('lanDespesa'): null;
    $lanTipo       = ($this->input->post('lanTipo') != "") ? $this->input->post('lanTipo'): null;
    $lanCategoria  = ($this->input->post('lanCategoria') != "") ? $this->input->post('lanCategoria'): null;
    $lanVencimento = (strlen($this->input->post('lanVencimento')) == 10) ? acerta_data($this->input->post('lanVencimento')): null;
    $lanValor      = ($this->input->post('lanValor') != "") ? acerta_moeda($this->input->post('lanValor')): null;
    $lanPagamento  = (strlen($this->input->post('lanPagamento')) == 10) ? acerta_data($this->input->post('lanPagamento')): null;
    $lanValorPago  = ($this->input->post('lanValorPago') != "") ? acerta_moeda($this->input->post('lanValorPago')): null;
    $lanConta      = ($this->input->post('lanConta') != "") ? $this->input->post('lanConta'): null;
    $lanObservacao = ($this->input->post('lanObservacao') != "") ? $this->input->post('lanObservacao'): null;
    // ======================

    $Lancamento = [];
    $Lancamento["lan_despesa"]    = $lanDespesa;
    $Lancamento["lan_tipo"]       = $lanTipo;
    $Lancamento["lan_categoria"]  = $lanCategoria;
    $Lancamento["lan_vencimento"] = $lanVencimento;
    $Lancamento["lan_valor"]      = $lanValor;
    $Lancamento["lan_pagamento"]  = $lanPagamento;
    $Lancamento["lan_valor_pago"] = $lanValorPago;
    $Lancamento["lan_conta"]      = $lanConta;
    $Lancamento["lan_observacao"] = $lanObservacao;

    $this->load->model('Tb_Lancamento');
    $retInsert = $this->Tb_Lancamento->insert($Lancamento);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Lançamento. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Lançamento inserido com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }

  public function jsonHtmlLancamentos(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlLancamentos"] = "";

    // variaveis ============
    $vMesBase    = $this->input->post('mes_base');
    $vAnoBase    = $this->input->post('ano_base');

    $vVctoIni    = $this->input->post('filterDtVctoIni');
    $vVctoFim    = $this->input->post('filterDtVctoFim');
    $vPgtoIni    = $this->input->post('filterDtPgtoIni');
    $vPgtoFim    = $this->input->post('filterDtPgtoFim');
    $vContaId    = $this->input->post('filterContas');
    $vTipo       = $this->input->post('filterTipo');
    $vCategoria  = $this->input->post('filterCategoria');
    $vPagas      = $this->input->post('filterApenasPagas');
    // ======================

    // filtro
    $arrFilters = [];

    if($vMesBase != ""){
      $arrFilters["mesBase"] = $vMesBase;
    }

    if($vAnoBase != ""){
      $arrFilters["anoBase"] = $vAnoBase;
    }

    if($vVctoIni != ""){
      $arrFilters["vctoIni"] = acerta_data($vVctoIni);
    }

    if($vVctoFim != ""){
      $arrFilters["vctoFim"] = acerta_data($vVctoFim);
    }

    if($vPgtoIni != ""){
      $arrFilters["pgtoIni"] = acerta_data($vPgtoIni);
    }

    if($vPgtoFim != ""){
      $arrFilters["pgtoFim"] = acerta_data($vPgtoFim);
    }

    if($vContaId != ""){
      $arrFilters["conta"] = $vContaId;
    }

    if($vTipo != ""){
      $arrFilters["tipo"] = $vTipo;
    }

    if($vCategoria != ""){
      $arrFilters["categoria"] = $vCategoria;
    }

    if($vPagas != ""){
      $arrFilters["apenasPagas"] = $vPagas;
    }
    // ======

    $this->load->model('Tb_Lancamento');
    $htmlContasRecebTable = $this->Tb_Lancamento->getHtmlLancamentos($arrFilters);
    $arrRet["htmlLancamentos"] = $htmlContasRecebTable;

    $htmlTotaisGastos           = $this->Tb_Lancamento->getHtmlTotaisGastos($arrFilters);
    $arrRet["htmlTotaisGastos"] = $htmlTotaisGastos;

    echo json_encode($arrRet);
  }

  public function jsonDelLancamento(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $vLanId = $this->input->post('lanId');
    // ======================

    $this->load->model('Tb_Lancamento');

    $retDelete = $this->Tb_Lancamento->delete($vLanId);
    if( $retDelete["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao deletar Parcela. Msg: " . $retDelete["msg"];

      echo json_encode($arrRet);
      return;
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlEditLancamento(){
    $data   = [];
    $arrRet = [];
    $arrRet["html"] = "";
    $arrRet["msg"]  = "";
    $arrRet["erro"] = false;

    $lanId = $this->input->post('lanId');

    $this->load->model('Tb_Lancamento');
    $retLancamento = $this->Tb_Lancamento->getLancamento($lanId);
    if($retLancamento["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar o Lançamento. Msg: " . $retLancamento["msg"];
    } else {
      $Lancamento         = $retLancamento["arrLancamentoDados"];
      $data["Lancamento"] = $Lancamento;
      $data["editar"]     = true;
    }

    $this->load->model('Tb_Base_Despesa');
    $retBaseDesp = $this->Tb_Base_Despesa->getBaseDespesas();
    $arrBaseDesp = (!$retBaseDesp["erro"]) ? $retBaseDesp["arrBaseDespesas"]: array();
    $data["arrBaseDesp"] = $arrBaseDesp;

    $this->load->model('Tb_Conta');
    $retConta = $this->Tb_Conta->getContas();
    $arrConta = (!$retConta["erro"]) ? $retConta["arrContas"]: array();
    $data["arrConta"] = $arrConta;

    $htmlView       = $this->load->view('Lancamentos/novo', $data, true);
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonEditLancamento(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $lanId         = $this->input->post('lanId') > 0 ? $this->input->post('lanId'): -1;
    $lanDespesa    = ($this->input->post('lanDespesa') != "") ? $this->input->post('lanDespesa'): null;
    $lanTipo       = ($this->input->post('lanTipo') != "") ? $this->input->post('lanTipo'): null;
    $lanCategoria  = ($this->input->post('lanCategoria') != "") ? $this->input->post('lanCategoria'): null;
    $lanVencimento = (strlen($this->input->post('lanVencimento')) == 10) ? acerta_data($this->input->post('lanVencimento')): null;
    $lanValor      = ($this->input->post('lanValor') != "") ? acerta_moeda($this->input->post('lanValor')): null;
    $lanPagamento  = (strlen($this->input->post('lanPagamento')) == 10) ? acerta_data($this->input->post('lanPagamento')): null;
    $lanValorPago  = ($this->input->post('lanValorPago') != "") ? acerta_moeda($this->input->post('lanValorPago')): null;
    $lanConta      = ($this->input->post('lanConta') != "") ? $this->input->post('lanConta'): null;
    $lanObservacao = ($this->input->post('lanObservacao') != "") ? $this->input->post('lanObservacao'): null;
    // ======================

    $this->load->model('Tb_Lancamento');
    $retLancamento = $this->Tb_Lancamento->getLancamento($lanId);
    if($retLancamento["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao encontrar Lançamento. Msg: " . $retLancamento["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $Lancamento = $retLancamento["arrLancamentoDados"];
    }

    $Lancamento["lan_despesa"]    = $lanDespesa;
    $Lancamento["lan_tipo"]       = $lanTipo;
    $Lancamento["lan_categoria"]  = $lanCategoria;
    $Lancamento["lan_vencimento"] = $lanVencimento;
    $Lancamento["lan_valor"]      = $lanValor;
    $Lancamento["lan_pagamento"]  = $lanPagamento;
    $Lancamento["lan_valor_pago"] = $lanValorPago;
    $Lancamento["lan_conta"]      = $lanConta;
    $Lancamento["lan_observacao"] = $lanObservacao;

    $retUpdate = $this->Tb_Lancamento->edit($Lancamento);
    if( $retUpdate["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao editar Lançamento. Msg: " . $retUpdate["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Lançamento editado com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }

  public function jsonHtmlCadPrevisao(){
    $data   = [];
    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlCadPrevisao"] = "";

    // variaveis ============
    $vMesBase = $this->input->post('mes_base');
    $vAnoBase = $this->input->post('ano_base');
    // ======================

    $this->load->model("Tb_Lancamento");
    $arrRetGastos         = $this->Tb_Lancamento->getArrCategoriaGastos($vMesBase, $vAnoBase);
    $data["arrRetGastos"] = $arrRetGastos;
    $data["mes_base"]     = $vMesBase;
    $data["ano_base"]     = $vAnoBase;

    $htmlView = $this->load->view('Lancamentos/cadPrevisao', $data, true);
    $arrRet["htmlCadPrevisao"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonPostCadPrevisao(){
    $this->load->helpers("utils");

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // parametros
    $mesBase = ($this->input->post('mes_base') != "") ? $this->input->post('mes_base'): null;
    $anoBase = ($this->input->post('ano_base') != "") ? $this->input->post('ano_base'): null;

    $arrInfoPrev = [];
    foreach($_POST as $key => $value){
      $ehPrevisao = strpos($key, "lanValor_") !== false;
      if($ehPrevisao){
        $arrKey  = explode("lanValor_", $key);
        $bdpId   = isset($arrKey[1]) ? $arrKey[1]: 0;
        $metaVlr = acerta_moeda($value);

        if($bdpId > 0){
          $metaVlr = (is_numeric($metaVlr)) ? $metaVlr: 0;
          $arrInfoPrev[$bdpId] = $metaVlr;
        }
      }
    }
    // ==========

    $this->load->model("Tb_Meta_Despesa");
    $retInsert = $this->Tb_Meta_Despesa->insertArray($mesBase, $anoBase, $arrInfoPrev);

    $arrRet["erro"] = $retInsert["erro"];
    $arrRet["msg"]  = $retInsert["msg"];

    echo json_encode($arrRet);
  }

  public function jsonHtmlAddTransferencia(){
    $data = [];

    $this->load->model('Tb_Conta');
    $retConta = $this->Tb_Conta->getContas();
    $arrConta = (!$retConta["erro"]) ? $retConta["arrContas"]: array();
    $data["arrConta"] = $arrConta;

    $htmlView = $this->load->view('Lancamentos/novaTransferencia', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonAddTransferencia(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $tVencimento = (strlen($this->input->post('tVencimento')) == 10) ? acerta_data($this->input->post('tVencimento')): null;
    $tValor      = ($this->input->post('tValor') != "") ? acerta_moeda($this->input->post('tValor')): null;
    $tContaDe    = ($this->input->post('tContaDe') != "") ? $this->input->post('tContaDe'): null;
    $tContaPara  = ($this->input->post('tContaPara') != "") ? $this->input->post('tContaPara'): null;
    // ======================

    $this->load->model('Tb_Lancamento');
    $retInsert = $this->Tb_Lancamento->insertTransferencia($tValor, $tVencimento, $tContaDe, $tContaPara);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Transferência. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Transferência inserida com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }
}
