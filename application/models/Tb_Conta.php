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

  private function validaInsert($arrConta){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $strNome = $arrConta["con_nome"] ?? "";
    if(strlen($strNome) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um nome de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $strSigla = $arrConta["con_sigla"] ?? "";
    if(strlen($strSigla) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma sigla de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $vDtSaldo = (isset($arrConta["con_data_saldo"])) ? $arrConta["con_data_saldo"]: "";
    $isDtSaldoValid = isValidDate($vDtSaldo, "Y-m-d");
    if(!$isDtSaldoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma data de saldo válida";

      return $arrRet;
    }

    $vSaldoIni = (isset($arrConta["con_saldo_inicial"])) ? (float)$arrConta["con_saldo_inicial"]: "";
    if(!is_numeric($vSaldoIni)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um saldo inicial válido!";

      return $arrRet;
    }

    $strAtivo = $arrConta["con_ativo"] ?? "";
    if($strAtivo != "0" && $strAtivo != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de ativo válida! Opções: 0, 1.";

      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrConta){
    $arrRet                = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["Conta"] = "";

    $retValidacao = $this->validaInsert($arrConta);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->helpers("utils");

    $vNome     = isset($arrConta["con_nome"]) ? $arrConta["con_nome"]: "";
    $vSigla    = isset($arrConta["con_sigla"]) ? $arrConta["con_sigla"]: "";
    $vDtSaldo  = isset($arrConta["con_data_saldo"]) ? $arrConta["con_data_saldo"]: "";
    $vSaldoIni = isset($arrConta["con_saldo_inicial"]) ? $arrConta["con_saldo_inicial"]: 0;
    $vAtivo    = isset($arrConta["con_ativo"]) ? $arrConta["con_ativo"]: 1;

    $data = array(
      'con_nome'          => $vNome,
      'con_sigla'         => $vSigla,
      'con_data_saldo'    => $vDtSaldo,
      'con_saldo_inicial' => $vSaldoIni,
      'con_ativo'         => $vAtivo,
    );

    $this->db->trans_start();
    $this->db->insert('tb_conta', $data);
    $conId = $this->db->insert_id();
    $this->db->trans_complete();
    $retInsert = $this->db->trans_status();

    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $retConta = $this->restGetConta($conId);

      $arrRet["Conta"] = ($retConta["erro"] == true) ? array(): $retConta["Conta"];
      $arrRet["erro"]  = false;
      $arrRet["msg"]   = "Conta inserida com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrConta){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $strId = $arrConta["con_id"] ?? "";
    if(!is_numeric($strId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um ID válido para edição!";

      return $arrRet;
    }

    $strNome = $arrConta["con_nome"] ?? "";
    if(strlen($strNome) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um nome de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $strSigla = $arrConta["con_sigla"] ?? "";
    if(strlen($strSigla) < 2){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma sigla de pelo menos 2 caracteres!";

      return $arrRet;
    }

    $vDtSaldo = (isset($arrConta["con_data_saldo"])) ? $arrConta["con_data_saldo"]: "";
    $isDtSaldoValid = isValidDate($vDtSaldo, "Y-m-d");
    if(!$isDtSaldoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma data de saldo válida";

      return $arrRet;
    }

    $vSaldoIni = (isset($arrConta["con_saldo_inicial"])) ? (float)$arrConta["con_saldo_inicial"]: "";
    if(!is_numeric($vSaldoIni)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe um saldo inicial válido!";

      return $arrRet;
    }

    $strAtivo = $arrConta["con_ativo"] ?? "";
    if($strAtivo != "0" && $strAtivo != "1"){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Informe uma informação de ativo válida! Opções: 0, 1.";

      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrConta){
    $arrRet          = [];
    $arrRet["erro"]  = true;
    $arrRet["msg"]   = "";
    $arrRet["Conta"] = "";

    $retValidacao = $this->validaEdit($arrConta);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->helpers("utils");

    $vId       = isset($arrConta["con_id"]) ? $arrConta["con_id"]: "";
    $vNome     = isset($arrConta["con_nome"]) ? $arrConta["con_nome"]: "";
    $vSigla    = isset($arrConta["con_sigla"]) ? $arrConta["con_sigla"]: "";
    $vDtSaldo  = isset($arrConta["con_data_saldo"]) ? $arrConta["con_data_saldo"]: "";
    $vSaldoIni = isset($arrConta["con_saldo_inicial"]) ? $arrConta["con_saldo_inicial"]: 0;
    $vAtivo    = isset($arrConta["con_ativo"]) ? $arrConta["con_ativo"]: 1;

    $data = array(
      'con_nome'          => $vNome,
      'con_sigla'         => $vSigla,
      'con_data_saldo'    => $vDtSaldo,
      'con_saldo_inicial' => $vSaldoIni,
      'con_ativo'         => $vAtivo,
    );

    $this->db->trans_start();
    $this->db->where('con_id', $vId);
    $retInsert = $this->db->update('tb_conta', $data);
    $this->db->trans_complete();
    $retEdit = $this->db->trans_status();

    if(!$retEdit){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $retConta = $this->restGetConta($vId);

      $arrRet["Conta"] = ($retConta["erro"] == true) ? array(): $retConta["Conta"];
      $arrRet["erro"]  = false;
      $arrRet["msg"]   = "Conta editada com sucesso!";
    }

    return $arrRet;
  }

  public function delete($id){
    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    if(!is_numeric($id)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar Conta";

      return $arrRet;
    }

    $this->load->database();
    $this->db->trans_start();
    $this->db->where('con_id', $id);
    $this->db->delete('tb_conta');
    $this->db->trans_complete();
    $retDelete = $this->db->trans_status();

    if(!$retDelete){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Conta deletada com sucesso!";
    }

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

  public function restGetConta($id){
    $arrRet = [];
    $arrRet["erro"]  = true;
    $arrRet["msg"]   = "";
    $arrRet["Conta"] = array();

    if(!is_numeric($id)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar Conta";

      return $arrRet;
    }

    #SELECT  FROM tb_base_despesa
    $this->load->database();
    $this->db->select("con_id, con_nome, con_sigla, con_data_saldo, con_saldo_inicial, con_ativo");
    $this->db->from("tb_conta");
    $this->db->where("con_id", $id);

    $query = $this->db->get();
    $row   = $query->row();

    if($query->num_rows() > 0 && isset($row)){
      $Conta = [];
      $Conta["id"]            = $row->con_id;
      $Conta["nome"]          = $row->con_nome;
      $Conta["sigla"]         = $row->con_sigla;
      $Conta["data_saldo"]    = $row->con_data_saldo;
      $Conta["saldo_inicial"] = $row->con_saldo_inicial;
      $Conta["ativo"]         = $row->con_ativo;

      $arrRet["erro"]  = false;
      $arrRet["msg"]   = "Conta encontrada com sucesso!";
      $arrRet["Conta"] = $Conta;

      return $arrRet;
    } else {
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Nenhuma Conta encontrada!";

      return $arrRet;
    }
  }

  public function restAddConta($Conta){
    return $this->insert($Conta);
  }

  public function restEditConta($Conta){
    return $this->edit($Conta);
  }

  public function restDeleteConta($id){
    $retConta = $this->restGetConta($id);
    $retDel   = $this->delete($id);

    $retDel["Conta"] = ($retConta["erro"]) ? array(): $retConta["Conta"];

    return $retDel;
  }
}
