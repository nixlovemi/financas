<?php
class Tb_Conta extends CI_Model {
  public function getContas(){
    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["arrContas"] = array();

    $this->load->database();
    $this->db->select("con_id, con_nome, con_sigla");
    $this->db->from("tb_conta");
    $this->db->where("con_ativo", 1);
    $this->db->order_by("con_nome", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrContas = [];
        $arrContas["con_id"]    = $rs1["con_id"];
        $arrContas["con_nome"]  = $rs1["con_nome"];
        $arrContas["con_sigla"] = $rs1["con_sigla"];

        $arrRet["arrContas"][] = $arrContas;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getConta($conId){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";
    $arrRet["arrContaDados"] = array();

    if(!is_numeric($conId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar a conta!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("con_id, con_nome, con_sigla, con_saldo_inicial, con_ativo");
    $this->db->from("tb_conta");
    $this->db->where("con_id", $conId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrContaDados = [];
      $arrContaDados["con_id"]            = $row->con_id;
      $arrContaDados["con_nome"]          = $row->con_nome;
      $arrContaDados["con_sigla"]         = $row->con_sigla;
      $arrContaDados["con_saldo_inicial"] = $row->con_saldo_inicial;
      $arrContaDados["con_ativo"]         = $row->con_ativo;

      $arrRet["arrContaDados"] = $arrContaDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getSaldoContas($mes, $ano){
    $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);

    $arrRet = [];
    $arrRet["erro"]      = true;
    $arrRet["msg"]       = "";
    $arrRet["mes"]       = $mes;
    $arrRet["ano"]       = $ano;
    $arrRet["arrContas"] = array();

    $mesAnoNumericos    = is_numeric($mes) && is_numeric($ano);
    $mesAnoTamanhoCerto = strlen($mes) == 2 && strlen($ano) == 4;
    if( !$mesAnoNumericos || !$mesAnoTamanhoCerto ){
      $arrRet["erro"]      = true;
      $arrRet["msg"]       = "Informe o mês/ano corretamente!";
      return $arrRet;
    }

    $dtBase    = $ano . "-" . $mes . '-01';
    $dataFinal = date("Y-m-t", strtotime($dtBase));
    $where     = (strlen($dataFinal) == 10) ? " AND lan_pagamento <= '$dataFinal' ": "";

    $this->load->database();
    $this->db->select("
      con_id
      , con_nome
      , con_sigla
      , con_saldo_inicial
      , con_data_saldo
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'R' $where) AS receitas
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'R' AND lan_pagamento >= '$dtBase' AND lan_pagamento <= '$dataFinal') AS receitas_mes
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'D' $where) AS despesas
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'D' AND lan_pagamento >= '$dtBase' AND lan_pagamento <= '$dataFinal') AS despesas_mes
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'T' $where) AS transferencias
      ,(SELECT ROUND(COALESCE(SUM(lan_valor_pago), 0), 2) FROM tb_lancamento WHERE lan_conta = con_id AND lan_tipo = 'T' AND lan_pagamento >= '$dtBase' AND lan_pagamento <= '$dataFinal') AS transferencias_mes
    ");
    $this->db->from("tb_conta");
    $this->db->where("con_ativo", 1);
    $this->db->order_by("con_nome", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrContas = [];
        $arrContas["con_id"]             = $rs1["con_id"];
        $arrContas["con_nome"]           = $rs1["con_nome"];
        $arrContas["con_sigla"]          = $rs1["con_sigla"];
        $arrContas["con_saldo_inicial"]  = $rs1["con_saldo_inicial"];
        $arrContas["con_data_saldo"]     = $rs1["con_data_saldo"];
        $arrContas["receitas"]           = $rs1["receitas"];
        $arrContas["receitas_mes"]       = $rs1["receitas_mes"];
        $arrContas["despesas"]           = $rs1["despesas"];
        $arrContas["despesas_mes"]       = $rs1["despesas_mes"];
        $arrContas["transferencias"]     = $rs1["transferencias"];
        $arrContas["transferencias_mes"] = $rs1["transferencias_mes"];
        $arrContas["saldo"]              = ($rs1["con_saldo_inicial"] + $rs1["receitas"] + $rs1["transferencias"]) - $rs1["despesas"];

        $arrRet["arrContas"][] = $arrContas;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getHtmlSaldoContas($mes, $ano){
    $html        = "";
    $mes         = str_pad($mes, 2, "0", STR_PAD_LEFT);
    $arrRetSaldo = $this->getSaldoContas($mes, $ano);

    // pega dados do mes anterior
    $dtMesAnterior       = date('Y-m-d', strtotime('-1 day', strtotime($ano . "-" . $mes . "-01")));
    $mesAnterior         = date('m', strtotime($dtMesAnterior));
    $anoAnterior         = date('Y', strtotime($dtMesAnterior));
    $arrRetSaldoAnterior = $this->getSaldoContas($mesAnterior, $anoAnterior);
    // ==========================

    if( $arrRetSaldo["erro"] == false ){
      $arrContas = $arrRetSaldo["arrContas"];
      if(count($arrContas) > 0){
        $html .= "<table class='table table-bordered' id=''>";
        $html .= "  <thead>";
        $html .= "    <tr>";
        $html .= "      <th>Conta</th>";
        $html .= "      <th>Sigla</th>";
        $html .= "      <th>Saldo Inicial</th>";
        $html .= "      <th>Receitas do Mês</th>";
        $html .= "      <th>Despesas do Mês</th>";
        $html .= "      <th>Transferências do Mês</th>";
        $html .= "      <th>Saldo em $mes/$ano</th>";
        $html .= "    </tr>";
        $html .= "  </thead>";
        $html .= "  <tbody>";

        $totReceita = 0;
        $totDespesa = 0;
        $totTransf  = 0;
        $totSaldo   = 0;

        for($i=0; $i<count($arrContas); $i++){
          $Conta = $arrContas[$i];

          $contaDtSaldo = $Conta["con_data_saldo"];
          $contaDesc    = $Conta["con_nome"];
          $contaSigla   = $Conta["con_sigla"];
          $contaSaldo   = $Conta["saldo"];
          $receitasMes  = $Conta["receitas_mes"];
          $despesasMes  = $Conta["despesas_mes"];
          $trasnfMes    = $Conta["transferencias_mes"];
          $saldoInicial = $arrRetSaldoAnterior["arrContas"][$i]["saldo"];

          if($ano . "-" . $mes . "-01" < $contaDtSaldo){
            $contaSaldo = 0;
            $saldoInicial = 0;
          }

          $totReceita += $receitasMes;
          $totDespesa += $despesasMes;
          $totTransf  += $trasnfMes;
          $totSaldo   += $contaSaldo;

          $strSaldo      = is_numeric($contaSaldo) ? "R$" . number_format($contaSaldo, 2, ",", "."): "-";
          $strReceitaMes = is_numeric($receitasMes) ? "R$" . number_format($receitasMes, 2, ",", "."): "-";
          $strDespesaMes = is_numeric($despesasMes) ? "R$" . number_format($despesasMes, 2, ",", "."): "-";
          $strTransfMes  = is_numeric($trasnfMes) ? "R$" . number_format($trasnfMes, 2, ",", "."): "-";
          $strSaldoIni   = is_numeric($saldoInicial) ? "R$" . number_format($saldoInicial, 2, ",", "."): "-";

          $html .= "  <tr>";
          $html .= "    <td>$contaDesc</td>";
          $html .= "    <td>$contaSigla</td>";
          $html .= "    <td>$strSaldoIni</td>";
          $html .= "    <td>$strReceitaMes</td>";
          $html .= "    <td>$strDespesaMes</td>";
          $html .= "    <td>$strTransfMes</td>";
          $html .= "    <td>$strSaldo</td>";
          $html .= "  </tr>";
        }

        $strTotReceita = "R$" . number_format($totReceita, 2, ",", ".");
        $strTotDespesa = "R$" . number_format($totDespesa, 2, ",", ".");
        $strTotTransf  = "R$" . number_format($totTransf, 2, ",", ".");
        $strTotSaldo   = "R$" . number_format($totSaldo, 2, ",", ".");

        $html .= "    <tr style='font-weight:bold'>";
        $html .= "      <td align='center' colspan='3'>TOTAIS</td>";
        $html .= "      <td>$strTotReceita</td>";
        $html .= "      <td>$strTotDespesa</td>";
        $html .= "      <td>$strTotTransf</td>";
        $html .= "      <td>$strTotSaldo</td>";
        $html .= "    </tr>";
        $html .= "  </tbody>";
        $html .= "</table>";
      }
    }

    return $html;
  }
}
