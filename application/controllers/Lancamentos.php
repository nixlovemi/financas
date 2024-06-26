<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lancamentos extends MY_Controller {
  public function __construct(){
    MY_Controller::__construct();
  }

  public function index(){
    $data = [];

    $vMesBase = date("m");
    $vAnoBase = date("Y");

    $this->load->model('Tb_Lancamento');
    $htmlLancamentos         = $this->Tb_Lancamento->getHtmlLancamentos();
    $data["htmlLancamentos"] = $htmlLancamentos;

    $htmlTotaisGastos         = $this->Tb_Lancamento->getHtmlTotaisGastos();
    $data["htmlTotaisGastos"] = $htmlTotaisGastos;

    $this->load->model('Tb_Conta');
    $htmlSaldoContas         = $this->Tb_Conta->getHtmlSaldoContas($vMesBase, $vAnoBase);
    $data["htmlSaldoContas"] = $htmlSaldoContas;

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
    $lanParcela    = ($this->input->post('lanParcela') != "") ? $this->input->post('lanParcela'): null;
    $lanDespesa    = ($this->input->post('lanDespesa') != "") ? $this->input->post('lanDespesa'): null;
    $lanTipo       = ($this->input->post('lanTipo') != "") ? $this->input->post('lanTipo'): null;
    $lanCompra     = (strlen($this->input->post('lanCompra')) == 10) ? acerta_data($this->input->post('lanCompra')): null;
    $lanVencimento = (strlen($this->input->post('lanVencimento')) == 10) ? acerta_data($this->input->post('lanVencimento')): null;
    $lanValor      = ($this->input->post('lanValor') != "") ? acerta_moeda($this->input->post('lanValor')): null;
    $lanPagamento  = (strlen($this->input->post('lanPagamento')) == 10) ? acerta_data($this->input->post('lanPagamento')): null;
    $lanValorPago  = ($this->input->post('lanValorPago') != "") ? acerta_moeda($this->input->post('lanValorPago')): null;
    $lanConta      = ($this->input->post('lanConta') != "") ? $this->input->post('lanConta'): null;
    $lanObservacao = ($this->input->post('lanObservacao') != "") ? $this->input->post('lanObservacao'): null;
    $lanConfirmado = ($this->input->post('lanConfirmado') != "") ? $this->input->post('lanConfirmado'): 0;
    $repeteMeses   = (is_numeric($this->input->post('repeteMeses')) && $this->input->post('repeteMeses') > 0) ? $this->input->post('repeteMeses'): null;

    $arrCategorias    = (is_array($this->input->post('ldBdpId'))) ? $this->input->post('ldBdpId'): array();
    $arrCategoriasVlr = (is_array($this->input->post('ldValor'))) ? $this->input->post('ldValor'): array();
    foreach ($arrCategoriasVlr as &$valor) {
      $valor = acerta_moeda($valor);
    }
    // ======================

    $Lancamento = [];
    $Lancamento["lan_parcela"]    = $lanParcela;
    $Lancamento["lan_despesa"]    = $lanDespesa;
    $Lancamento["lan_tipo"]       = $lanTipo;
    $Lancamento["lan_compra"]     = $lanCompra;
    $Lancamento["lan_vencimento"] = $lanVencimento;
    $Lancamento["lan_valor"]      = $lanValor;
    $Lancamento["lan_pagamento"]  = $lanPagamento;
    $Lancamento["lan_valor_pago"] = $lanValorPago;
    $Lancamento["lan_conta"]      = $lanConta;
    $Lancamento["lan_observacao"] = $lanObservacao;
    $Lancamento["lan_confirmado"] = $lanConfirmado;
    $Lancamento["ld_bdp_id"]      = $arrCategorias;
    $Lancamento["ld_valor"]       = $arrCategoriasVlr;

    $this->load->model('Tb_Lancamento');
    $retInsert = $this->Tb_Lancamento->insert($Lancamento, $repeteMeses);

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
    $vDescricao  = $this->input->post('filterDescricao');
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

    if($vDescricao != ""){
      $arrFilters["descricao"] = $vDescricao;
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

    $this->load->model('Tb_Conta');
    $htmlSaldoContas           = $this->Tb_Conta->getHtmlSaldoContas($vMesBase, $vAnoBase);
    $arrRet["htmlSaldoContas"] = $htmlSaldoContas;

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
    $lanParcela    = ($this->input->post('lanParcela') != "") ? $this->input->post('lanParcela'): null;
    $lanDespesa    = ($this->input->post('lanDespesa') != "") ? $this->input->post('lanDespesa'): null;
    $lanTipo       = ($this->input->post('lanTipo') != "") ? $this->input->post('lanTipo'): null;
    $lanCompra     = (strlen($this->input->post('lanCompra')) == 10) ? acerta_data($this->input->post('lanCompra')): null;
    $lanVencimento = (strlen($this->input->post('lanVencimento')) == 10) ? acerta_data($this->input->post('lanVencimento')): null;
    $lanValor      = ($this->input->post('lanValor') != "") ? acerta_moeda($this->input->post('lanValor')): null;
    $lanPagamento  = (strlen($this->input->post('lanPagamento')) == 10) ? acerta_data($this->input->post('lanPagamento')): null;
    $lanValorPago  = ($this->input->post('lanValorPago') != "") ? acerta_moeda($this->input->post('lanValorPago')): null;
    $lanConta      = ($this->input->post('lanConta') != "") ? $this->input->post('lanConta'): null;
    $lanObservacao = ($this->input->post('lanObservacao') != "") ? $this->input->post('lanObservacao'): null;
    $lanConfirmado = ($this->input->post('lanConfirmado') != "") ? $this->input->post('lanConfirmado'): 0;

    $arrCategorias    = (is_array($this->input->post('ldBdpId'))) ? $this->input->post('ldBdpId'): array();
    $arrCategoriasVlr = (is_array($this->input->post('ldValor'))) ? $this->input->post('ldValor'): array();
    foreach ($arrCategoriasVlr as &$valor) {
      $valor = acerta_moeda($valor);
    }
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

    $Lancamento["lan_parcela"]    = $lanParcela;
    $Lancamento["lan_despesa"]    = $lanDespesa;
    $Lancamento["lan_tipo"]       = $lanTipo;
    $Lancamento["lan_compra"]     = $lanCompra;
    $Lancamento["lan_vencimento"] = $lanVencimento;
    $Lancamento["lan_valor"]      = $lanValor;
    $Lancamento["lan_pagamento"]  = $lanPagamento;
    $Lancamento["lan_valor_pago"] = $lanValorPago;
    $Lancamento["lan_conta"]      = $lanConta;
    $Lancamento["lan_observacao"] = $lanObservacao;
    $Lancamento["lan_confirmado"] = $lanConfirmado;
    $Lancamento["ld_bdp_id"]      = $arrCategorias;
    $Lancamento["ld_valor"]       = $arrCategoriasVlr;

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

  public function jsonHtmlBaixaLctoGrupo(){
    $data           = [];
    $arrRet         = [];
    $arrRet["html"] = "";

    // variaveis ===========
    $strLanIds = $this->input->post('strLanIds') != "" ? $this->input->post('strLanIds'): "";
    $arrLanId  = ($strLanIds != "") ? explode(",", $strLanIds): array();
    // =====================

    if(count($arrLanId) <= 0){
      $this->load->helpers("alerts");
      $arrRet["html"] = showWarning("Nenhum lan&ccedil;amento selecionado!");
    } else {
      $arrLanIdFiltered = [];
      foreach($arrLanId as $lanId){
        if(is_numeric($lanId)) {
            $arrLanIdFiltered[] = $lanId;
        }
      }

      $this->load->model("Tb_Conta");
      $retContas = $this->Tb_Conta->getContas();
      $arrContas = ($retContas["erro"] == false) ? $retContas["arrContas"]: array();
      $data["arrContas"] = $arrContas;

      $this->load->model("Tb_Lancamento");
      $retHtmlLcto      = $this->Tb_Lancamento->getHtmlBaixaGrupo($arrLanIdFiltered);
      $data["htmlLcto"] = $retHtmlLcto;

      $data["strLanIds"] = $strLanIds;
      $htmlView = $this->load->view('Lancamentos/htmlBaixaLctoGrupo', $data, true);

      $arrRet["html"] = $htmlView;
    }

    echo json_encode($arrRet);
  }

  public function jsonPostBaixaLctoGrupo()
  {
    $this->load->helpers("utils");
    $this->load->model("Tb_Lancamento");

    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "Erro";

    // variaveis ==============
    $lanIds       = $this->input->post('lan_ids') != "" ? $this->input->post('lan_ids'): "";
    $lanPagamento = $this->input->post('lanPagamento') != "" ? acerta_data($this->input->post('lanPagamento')): "";
    $lanConta     = is_numeric($this->input->post('lanConta')) ? $this->input->post('lanConta'): "";
    // ========================

    if($lanIds == ""){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Nenhum lan&ccedil;amento selecionado!";
    } else {
      $arrLancamentos = explode(",", $lanIds);
      if(count($arrLancamentos) <= 0){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Nenhum lan&ccedil;amento selecionado!";
      } else {
        foreach($arrLancamentos as $lanId){
          $retLancamento = $this->Tb_Lancamento->getLancamento($lanId);
          if(!$retLancamento["erro"]){
            $Lancamento = $retLancamento["arrLancamentoDados"];
            $Lancamento["lan_pagamento"]  = $lanPagamento;
            $Lancamento["lan_valor_pago"] = $Lancamento["lan_valor"];
            $Lancamento["lan_conta"]      = $lanConta;

            $this->Tb_Lancamento->edit($Lancamento);
          }
        }

        $arrRet["erro"] = false;
        $arrRet["msg"]  = "Lan&ccedil;amentos baixados com sucesso!";
      }
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlDeletaLctoGrupo()
  {
    $data           = [];
    $arrRet         = [];
    $arrRet["html"] = "";

    // variaveis ===========
    $strLanIds = $this->input->post('strLanIds') != "" ? $this->input->post('strLanIds'): "";
    $arrLanId  = ($strLanIds != "") ? explode(",", $strLanIds): array();
    // =====================

    if(count($arrLanId) <= 0){
      $this->load->helpers("alerts");
      $arrRet["html"] = showWarning("Nenhum lan&ccedil;amento selecionado!");
    } else {
      $arrLanIdFiltered = [];
      foreach($arrLanId as $lanId){
        if(is_numeric($lanId)) {
            $arrLanIdFiltered[] = $lanId;
        }
      }

      $this->load->model("Tb_Conta");
      $retContas = $this->Tb_Conta->getContas();
      $arrContas = ($retContas["erro"] == false) ? $retContas["arrContas"]: array();
      $data["arrContas"] = $arrContas;

      $this->load->model("Tb_Lancamento");
      $retHtmlLcto      = $this->Tb_Lancamento->getHtmlBaixaGrupo($arrLanIdFiltered);
      $data["htmlLcto"] = $retHtmlLcto;

      $data["strLanIds"] = $strLanIds;
      $htmlView = $this->load->view('Lancamentos/htmlDeletaLctoGrupo', $data, true);

      $arrRet["html"] = $htmlView;
    }

    echo json_encode($arrRet);
  }
  
  public function jsonPostDeletaLctoGrupo()
  {
    $this->load->helpers("utils");
    $this->load->model("Tb_Lancamento");

    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "Erro";

    // variaveis ==============
    $lanIds       = $this->input->post('lan_ids') != "" ? $this->input->post('lan_ids'): "";
    // ========================

    if($lanIds == ""){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Nenhum lan&ccedil;amento selecionado!";
    } else {
      $arrLancamentos = explode(",", $lanIds);
      if(count($arrLancamentos) <= 0){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Nenhum lan&ccedil;amento selecionado!";
      } else {
        $strErro        = "";
        $arrRet["erro"] = false;
        
        foreach($arrLancamentos as $lanId){
          $retLancamento = $this->Tb_Lancamento->delete($lanId);
          if($retLancamento["erro"]){
            $arrRet["erro"] = true;
            $strErro       .= "Lcto ID $lanId=" . $retLancamento["msg"]. ". ";
          }
        }
        
        if($arrRet["erro"]){
            $arrRet["msg"]  = $strErro;
        } else {
            $arrRet["msg"]  = "Lan&ccedil;amentos baixados com sucesso!";
        }
      }
    }

    echo json_encode($arrRet);
  }

  public function xlsLcto($jsonFilterC)
  {
      require_once(APPPATH . '/helpers/utils_helper.php');
      $jsonFilter = base64url_decode($jsonFilterC);
      $arrFilters = json_decode($jsonFilter, true);
      
      $this->load->model("Tb_Lancamento");
      $ret  = $this->Tb_Lancamento->getHtmlLancamentos($arrFilters, false, true); //["rows"] | ["totais"] | ["limit"] | ["offset"]
      $rows = $ret["rows"] ?? array();

      $html = "";
      if(count($rows) > 0){
        $arquivo = "XlsLancamentos.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        // Se for o IE9, isso talvez seja necessário
        header('Cache-Control: max-age=1');

        $html .= "<table border='1'>";
        $html .= "  <tr>";
        $html .= "    <td>Despesa</td>";
        $html .= "    <td>Tipo</td>";
        $html .= "    <td>Categoria</td>";
        $html .= "    <td>Parcela</td>";
        $html .= "    <td>Dt Compra</td>";
        $html .= "    <td>Vencimento</td>";
        $html .= "    <td>Valor</td>";
        $html .= "    <td>Pagamento</td>";
        $html .= "    <td>Valor Pago</td>";
        $html .= "    <td>Conta</td>";
        $html .= "  </tr>";
        foreach($rows as $rs){
          $despesa    = $rs["lanDespesa"] ?? "";
          $tipo       = $rs["tipo"] ?? "";
          $categoria  = $rs["despesa"] ?? "";
          $parcela    = $rs["parcNr"] ?? "";
          $compra     = $rs["lanCompra"] ?? "";
          $vencimento = $rs["lanVcto"] ?? "";
          $valor      = $rs["lanValor"] ?? "";
          $pagamento  = $rs["lanPgto"] ?? "";
          $valor_pago = $rs["lanVlrPg"] ?? "";
          $conta      = $rs["conta"] ?? "";

          $html .= "<tr>";
          $html .= "  <td>".utf8_decode($despesa)."</td>";
          $html .= "  <td>".utf8_decode($tipo)."</td>";
          $html .= "  <td>".utf8_decode($categoria)."</td>";
          $html .= "  <td>".utf8_decode($parcela)."</td>";
          $html .= "  <td>$compra</td>";
          $html .= "  <td>$vencimento</td>";
          $html .= "  <td>$valor</td>";
          $html .= "  <td>$pagamento</td>";
          $html .= "  <td>$valor_pago</td>";
          $html .= "  <td>".utf8_decode($conta)."</td>";
          $html .= "</tr>";
        }
        $html .= "</table>";
      } else {
        $html .= "Nenhum resultado!";
      }

      //@todo vou montar aqui o XLS mas poderia ser numa view
      echo $html;
  }
}