<?php
class Tb_Lancamento extends CI_Model {
  public function getHtmlLancamentos($arrFilters=array(), $edit=true, $returnJson=false){
    require_once(APPPATH . '/helpers/utils_helper.php');

    // filtros
    $vMesBase   = isset($arrFilters["mesBase"]) ? $arrFilters["mesBase"]: (int) date("m");
    $vAnoBase   = isset($arrFilters["anoBase"]) ? $arrFilters["anoBase"]: (int) date("Y");

    $vVctoIni   = isset($arrFilters["vctoIni"]) ? $arrFilters["vctoIni"]: "";
    $vVctoFim   = isset($arrFilters["vctoFim"]) ? $arrFilters["vctoFim"]: "";
    $vPgtoIni   = isset($arrFilters["pgtoIni"]) ? $arrFilters["pgtoIni"]: "";
    $vPgtoFim   = isset($arrFilters["pgtoFim"]) ? $arrFilters["pgtoFim"]: "";
    $vDescricao = isset($arrFilters["descricao"]) ? $arrFilters["descricao"]: "";
    $vConta     = isset($arrFilters["conta"]) ? $arrFilters["conta"]: "";
    $vTipo      = isset($arrFilters["tipo"]) ? $arrFilters["tipo"]: "";
    $vCategoria = isset($arrFilters["categoria"]) ? $arrFilters["categoria"]: "";
    $vPagas     = isset($arrFilters["apenasPagas"]) ? $arrFilters["apenasPagas"]: "";

    $vLimit     = isset($arrFilters["limit"]) ? $arrFilters["limit"]: "";
    $vOffset    = isset($arrFilters["offset"]) ? $arrFilters["offset"]: "";
    // =======

    // sql filter
    $sqlFilter = "";

    if($vVctoIni != ""){
      $vMesBase   = "";
      $vAnoBase   = "";
      $sqlFilter .= " AND lan_vencimento >= '$vVctoIni' ";
    }

    if($vVctoFim != ""){
      $vMesBase   = "";
      $vAnoBase   = "";
      $sqlFilter .= " AND lan_vencimento <= '$vVctoFim' ";
    }

    if($vPgtoIni != ""){
      $vMesBase   = "";
      $vAnoBase   = "";
      $sqlFilter .= " AND lan_pagamento >= '$vPgtoIni' ";
    }

    if($vPgtoFim != ""){
      $vMesBase   = "";
      $vAnoBase   = "";
      $sqlFilter .= " AND lan_pagamento <= '$vPgtoFim' ";
    }

    if($vMesBase != ""){
      $sqlFilter .= " AND EXTRACT(MONTH FROM COALESCE(lan_pagamento, lan_vencimento)) = $vMesBase ";
    }

    if($vAnoBase != ""){
      $sqlFilter .= " AND EXTRACT(YEAR FROM COALESCE(lan_pagamento, lan_vencimento)) = $vAnoBase ";
    }

    if($vDescricao != ""){
      $sqlFilter .= " AND lan_despesa LIKE '%$vDescricao%' ";
    }

    if(is_numeric($vConta)){
      $sqlFilter .= " AND lan_conta = $vConta ";
    }

    if($vTipo != ""){
      $sqlFilter .= " AND lan_tipo = '$vTipo' ";
    }

    if(is_numeric($vCategoria)){
      $sqlFilter .= " AND lan_id IN (SELECT ld_lan_id FROM tb_lancamento_despesa WHERE ld_bdp_id = $vCategoria) ";
    }

    if($vPagas == "S"){
      $sqlFilter .= " AND lan_pagamento IS NOT NULL ";
    } else if ($vPagas == "N"){
      $sqlFilter .= " AND lan_pagamento IS NULL ";
    }
    // ==========

    $arrJsonRet           = [];
    $arrJsonRet["rows"]   = [];
    $arrJsonRet["totais"] = [];
    $arrJsonRet["limit"]  = $vLimit;
    $arrJsonRet["offset"] = $vOffset;

    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<div style='display:block; margin:8px 0 15px 4px'>";
    $htmlTable .= "  <a class='btn btn-info' href='javascript:;' id='btnBaixaLctoGrupo'>DAR BAIXA EM GRUPO</a>";
    $htmlTable .= "  <a class='btn btn-info' href='javascript:;' id='btnDeletaLctoGrupo'>DELETAR SELECIONADOS</a>";
    $htmlTable .= "  <a class='btn btn-success' href='javascript:;' id='btnXlsLctoGrupo'>GERAR XLS  </a>";
    $htmlTable .= "</div>";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th><input type='checkbox' id='ckbLancamentosTodos' value='T' /></th>";
    $htmlTable .= "      <th>ID</th>";
    $htmlTable .= "      <th>Descrição</th>";
    $htmlTable .= "      <th>Tipo</th>";
    $htmlTable .= "      <th>Parcela</th>";
    $htmlTable .= "      <th>Vencimento</th>";
    $htmlTable .= "      <th>Valor</th>";
    $htmlTable .= "      <th>Categoria</th>";
    $htmlTable .= "      <th>Pagamento</th>";
    $htmlTable .= "      <th>Valor Pg</th>";
    $htmlTable .= "      <th>Conta</th>";
    if($edit){
      $htmlTable .= "    <th width='7%'>Alterar</th>";
      $htmlTable .= "    <th width='7%'>Deletar</th>";
    }
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT lan_id ";
    $vSql .= "        , lan_despesa ";
    $vSql .= "        , CASE
                          WHEN lan_tipo = 'D' THEN \"Despesa\"
                          WHEN lan_tipo = 'R' THEN \"Receita\"
                          WHEN lan_tipo = 'T' THEN \"Transferência\"
                          ELSE \"**\"
                      END AS tipo ";
    $vSql .= "        , lan_parcela ";
    $vSql .= "        , lan_vencimento ";
    $vSql .= "        , lan_valor ";
    $vSql .= "        , lan_pagamento ";
    $vSql .= "        , lan_valor_pago ";
    $vSql .= "        , con_sigla ";
    $vSql .= "        , lan_confirmado ";
    $vSql .= "        , lan_compra ";
    $vSql .= " FROM tb_lancamento ";
    $vSql .= " LEFT JOIN tb_conta ON con_id = lan_conta ";
    $vSql .= " WHERE 1=1 ";
    $vSql .= " $sqlFilter ";
    $vSql .= " ORDER BY lan_pagamento, lan_vencimento, lan_compra, lan_id ";
    if($vLimit <> '' && is_numeric($vLimit)){
      $vSql .= " LIMIT $vLimit ";
    }
    if($vOffset <> '' && is_numeric($vOffset)){
      $vSql .= " OFFSET $vOffset ";
    }

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    $totValorRec      = 0;
    $totValorPgRec    = 0;
    $totValorDesp     = 0;
    $totValorPgDesp   = 0;
    $totNaoContabRec  = 0;
    $totNaoContabDesp = 0;

    if(count($arrRs) <= 0){
    } else {
      foreach($arrRs as $rs1){
        $query2 = $this->db->query(<<<SQL
           SELECT bdp_descricao, bdp_contabiliza, ld_valor
           FROM tb_base_despesa
           INNER JOIN tb_lancamento_despesa ON (ld_bdp_id = bdp_id)
           WHERE ld_lan_id = {$rs1["lan_id"]}
        SQL);
        $arrRs2 = $query2->result_array();
        $arrCategoria = [];
        $arrSpanValor = [];
        foreach($arrRs2 as $rs2){
          $arrCategoria[] = $rs2["bdp_descricao"];
          $arrSpanValor[] = $rs2["bdp_descricao"] . ":" . CURRENCY_SYMBOL . " " . number_format($rs2["ld_valor"], 2, ",", ".");

          if($rs2["bdp_contabiliza"] == 1){
            if($rs1["tipo"] == "Despesa"){
              $totValorDesp   += $rs1["lan_valor"];
              $totValorPgDesp += $rs1["lan_valor_pago"];
            } else if($rs1["tipo"] == "Receita"){
              $totValorRec   += $rs1["lan_valor"];
              $totValorPgRec += $rs1["lan_valor_pago"];
            }
          } else {
            if($rs1["tipo"] == "Despesa"){
              $totNaoContabDesp += $rs1["lan_valor"];
            } else if($rs1["tipo"] == "Receita"){
              $totNaoContabRec += $rs1["lan_valor"];
            }
          }
        }

        $lanId      = $rs1["lan_id"];
        $lanDespesa = $rs1["lan_despesa"];
        $tipo       = $rs1["tipo"];
        $parcNr     = $rs1["lan_parcela"];
        $lanCompra  = (strlen($rs1["lan_compra"]) == 10) ? date("d/m/Y", strtotime($rs1["lan_compra"])): "";
        $lanVcto    = (strlen($rs1["lan_vencimento"]) == 10) ? date("d/m/Y", strtotime($rs1["lan_vencimento"])): "";
        $lanValor   = (is_numeric($rs1["lan_valor"])) ? CURRENCY_SYMBOL . " " . number_format($rs1["lan_valor"], 2, ",", "."): "";
        $despesa    = implode(", ", $arrCategoria);
        $lanPgto    = (strlen($rs1["lan_pagamento"]) == 10) ? date("d/m/Y", strtotime($rs1["lan_pagamento"])): "";
        $lanVlrPg   = (is_numeric($rs1["lan_valor_pago"])) ? CURRENCY_SYMBOL . " " . number_format($rs1["lan_valor_pago"], 2, ",", "."): "";
        $conta      = $rs1["con_sigla"];

        $cssColor   = ($rs1["lan_confirmado"] == 1) ? "color:#3079ca;": "";
        $spnLanVcto = $lanVcto;
        if($lanCompra != '') {
            $spnLanVcto = "<span style='text-decoration: underline' title='Dt Compra: $lanCompra'>$spnLanVcto</span>";
        }

        $spnValor = $lanValor;
        if (count($arrSpanValor) > 1) {
          $spnValor = "<span style='text-decoration: underline' title='" . implode("\n", $arrSpanValor) . "'>$lanValor</span>";
        }

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td><input type='checkbox' name='ckbLancamentos' value='$lanId' /></td>";
        $htmlTable .= "  <td><span style='$cssColor'>$lanId</span></td>";
        $htmlTable .= "  <td><span style='$cssColor'>$lanDespesa</span></td>";
        $htmlTable .= "  <td>$tipo</td>";
        $htmlTable .= "  <td>$parcNr</td>";
        $htmlTable .= "  <td>$spnLanVcto</td>";
        $htmlTable .= "  <td>$spnValor</td>";
        $htmlTable .= "  <td>$despesa</td>";
        $htmlTable .= "  <td>$lanPgto</td>";
        $htmlTable .= "  <td>$lanVlrPg</td>";
        $htmlTable .= "  <td>$conta</td>";
        if($edit){
          $htmlTable .= "<td><a href='javascript:;' class='TbLancamento_ajax_alterar' data-id='$lanId'><i class='icon-edit icon-lista'></i></a></td>";
          $htmlTable .= "<td><a href='javascript:;' class='TbLancamento_ajax_deletar' data-id='$lanId'><i class='icon-trash icon-lista'></i></a></td>";
        }
        $htmlTable .= "</tr>";

        $arrJsonRet["rows"][] = array(
          "lanId"         => $lanId,
          "lanDespesa"    => $lanDespesa,
          "tipo"          => $tipo,
          "parcNr"        => $parcNr,
          "lanCompra"     => $lanCompra,
          "lanVcto"       => $lanVcto,
          "lanValor"      => $lanValor,
          "despesa"       => $despesa,
          "lanPgto"       => $lanPgto,
          "lanVlrPg"      => $lanVlrPg,
          "conta"         => $conta,
          "cssColor"      => $cssColor,
          "lanConfirmado" => $rs1["lan_confirmado"],
        );
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    $jsonFilter  = json_encode($arrFilters);
    $jsonFilterC = base64url_encode($jsonFilter);
    $htmlTable  .= "<input type='hidden' id='hddnXlsLcto' value='$jsonFilterC' />";

    $htmlTable .= "<br />";
    $htmlTable .= "<div class='widget-box widget-plain'>";
    $htmlTable .= "  <div class='center'>";
    $htmlTable .= "    <ul class='stat-boxes2'>";
    $htmlTable .= "      <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totValorRec, 2, ",", ".")."</strong>
                            Total Valor (Receita)
                          </div>
                        </li>";
    $htmlTable .= "     <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totValorPgRec, 2, ",", ".")."</strong>
                            Total Pago (Receita)
                          </div>
                        </li>";
    $htmlTable .= "     <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totNaoContabRec, 2, ",", ".")."</strong>
                            Total N&atilde;o Contabilizado (Receita)
                          </div>
                        </li>";
    $htmlTable .= "     <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totValorDesp, 2, ",", ".")."</strong>
                            Total Valor (Despesa)
                          </div>
                        </li>";
    $htmlTable .= "     <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totValorPgDesp, 2, ",", ".")."</strong>
                            Total Pago (Despesa)
                          </div>
                        </li>";
    $htmlTable .= "     <li>
                          <div class='left'>
                            <i style='font-size:43px;' class='icon icon-money'></i>
                          </div>
                          <div class='right'>
                            <strong>".CURRENCY_SYMBOL.number_format($totNaoContabDesp, 2, ",", ".")."</strong>
                            Total N&atilde;o Contabilizado (Despesa)
                          </div>
                        </li>";
    $htmlTable .= "    </ul>";
    $htmlTable .= "  </div>";
    $htmlTable .= "</div>";

    $arrJsonRet["totais"] = array(
      "receita"               => number_format($totValorRec, 2, ".", ""),
      "receitaPaga"           => number_format($totValorPgRec, 2, ".", ""),
      "receitaNaoContabiliza" => number_format($totNaoContabRec, 2, ".", ""),
      "despesa"               => number_format($totValorDesp, 2, ".", ""),
      "despesaPaga"           => number_format($totValorPgDesp, 2, ".", ""),
      "despesaNaoContabiliza" => number_format($totNaoContabDesp, 2, ".", ""),
    );

    if($returnJson == true){
      return $arrJsonRet;
    } else {
      return $htmlTable;
    }
  }

  public function getHtmlBaixaGrupo($arrLanId){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbGetHtmlBaixaGrupo'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th>ID</th>";
    $htmlTable .= "      <th>Descrição</th>";
    $htmlTable .= "      <th>Tipo</th>";
    $htmlTable .= "      <th>Parcela</th>";
    $htmlTable .= "      <th>Vencimento</th>";
    $htmlTable .= "      <th>Valor</th>";
    $htmlTable .= "      <th>Categoria</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT lan_id ";
    $vSql .= "        , lan_despesa ";
    $vSql .= "        , CASE
                          WHEN lan_tipo = 'D' THEN \"Despesa\"
                          WHEN lan_tipo = 'R' THEN \"Receita\"
                          WHEN lan_tipo = 'T' THEN \"Transferência\"
                          ELSE \"**\"
                      END AS tipo ";
    $vSql .= "        , lan_parcela ";
    $vSql .= "        , lan_vencimento ";
    $vSql .= "        , lan_valor ";
    $vSql .= "        , GROUP_CONCAT(bdp_descricao) AS bdp_descricao ";
    $vSql .= " FROM tb_lancamento ";
    $vSql .= " LEFT JOIN tb_lancamento_despesa ON (ld_lan_id = lan_id) ";
    $vSql .= " LEFT JOIN tb_base_despesa ON (bdp_id = ld_bdp_id) ";
    $vSql .= " LEFT JOIN tb_conta ON (con_id = lan_conta) ";
    $vSql .= " WHERE 1=1 ";
    $vSql .= " AND lan_id IN (" . implode(",", $arrLanId) . ")";
    $vSql .= " GROUP BY lan_id ";
    $vSql .= " ORDER BY lan_vencimento, lan_id ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    if(count($arrRs) <= 0){
    } else {
      $total = 0;
      foreach($arrRs as $rs1){
        $total += $rs1["lan_valor"];

        $lanId      = $rs1["lan_id"];
        $lanDespesa = $rs1["lan_despesa"];
        $tipo       = $rs1["tipo"];
        $parcNr     = $rs1["lan_parcela"];
        $lanVcto    = (strlen($rs1["lan_vencimento"]) == 10) ? date("d/m/Y", strtotime($rs1["lan_vencimento"])): "";
        $lanValor   = (is_numeric($rs1["lan_valor"])) ? CURRENCY_SYMBOL . " " . number_format($rs1["lan_valor"], 2, ",", "."): "";
        $despesa    = $rs1["bdp_descricao"];

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td><span style=''>$lanId</span></td>";
        $htmlTable .= "  <td><span style=''>$lanDespesa</span></td>";
        $htmlTable .= "  <td>$tipo</td>";
        $htmlTable .= "  <td>$parcNr</td>";
        $htmlTable .= "  <td>$lanVcto</td>";
        $htmlTable .= "  <td>$lanValor</td>";
        $htmlTable .= "  <td>$despesa</td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "<tr>";
    $htmlTable .= "  <td colspan='5' align='right'><b>TOTAL</b></td>";
    $htmlTable .= "  <td colspan='2'>".CURRENCY_SYMBOL." " . number_format($total, 2, ",", ".")."</td>";
    $htmlTable .= "</tr>";

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  private function getHtmlTbTotais($arrGastos){
    $html  = "";
    $html .= "<table class='table tableBordered' id=''>";
    $html .= "  <thead>";
    $html .= "    <tr style='font-weight: bold;'>";
    $html .= "      <td>Categoria</td>";
    $html .= "      <td>Previsto</td>";
    $html .= "      <td>Real</td>";
    $html .= "    </tr>";
    $html .= "  </thead>";
    $html .= "  <tbody>";

    $totPrevisto  = 0;
    $totRealizado = 0;

    foreach($arrGastos as $rs1){
      $previsto  = ($rs1["vlrPrevisto"] != "") ? $rs1["vlrPrevisto"]: 0;
      $realizado = ($rs1["vlrRealizado"] != "") ? $rs1["vlrRealizado"]: 0;
      $cssRed    = ($rs1["excedeuPrevisto"] == 1) ? "color:red;": "";
      $totPrevisto  += $previsto;
      $totRealizado += $realizado;

      $categoria    = $rs1["categoria"];
      $vlrPrevisto  = CURRENCY_SYMBOL.number_format($previsto, 2, ",", ".");
      $vlrRealizado = CURRENCY_SYMBOL.number_format($realizado, 2, ",", ".");

      $html .= "  <tr style='$cssRed'>";
      $html .= "    <td>$categoria</td>";
      $html .= "    <td>$vlrPrevisto</td>";
      $html .= "    <td>$vlrRealizado</td>";
      $html .= "  </tr>";
    }

    $html .= "    <tr>";
    $html .= "      <td colspan='3' style='background-color:black; color:white;'><center>TOTAL PREVISTO: ".CURRENCY_SYMBOL.number_format($totPrevisto, 2, ",", ".")."</center></td>";
    $html .= "    </tr>";
    $html .= "    <tr>";
    $html .= "      <td colspan='3' style='background-color:black; color:white;'><center>TOTAL REALIZADO: ".CURRENCY_SYMBOL.number_format($totRealizado, 2, ",", ".")."</center></td>";
    $html .= "    </tr>";
    $html .= "    <tr>";
    $html .= "      <td colspan='3' style='background-color:black; color:white;'><center>DIFERENÇA: ".CURRENCY_SYMBOL.number_format($totPrevisto - $totRealizado, 2, ",", ".")."</center></td>";
    $html .= "    </tr>";
    $html .= "  </tbody>";
    $html .= "</table>";

    return $html;
  }

  public function getArrCategoriaGastos($vMesBase, $vAnoBase){
    $this->load->database();

    $arrFixos        = [];
    $arrVariaveis    = [];
    $arrInvestimento = [];
    $idsNotIn        = "30";

    $vSql  = <<<SQL
      SELECT bdp_id AS id_categoria
            , bdp_descricao AS categoria
            , bdp_tipo AS tipo
            , ROUND(COALESCE(mdp_valor, 0), 2) AS previsto
            , ROUND(
                (
                  SELECT SUM(ld_valor)
                  FROM tb_lancamento_despesa
                  INNER JOIN tb_lancamento ON (ld_lan_id = lan_id AND lan_tipo = 'D' AND EXTRACT(MONTH FROM lan_vencimento) = $vMesBase AND EXTRACT(YEAR FROM lan_vencimento) = $vAnoBase)
                  WHERE ld_bdp_id = bdp_id
                )
              , 2) AS realizado
      FROM tb_base_despesa
      LEFT JOIN tb_meta_despesa ON (mdp_despesa = bdp_id AND mdp_mes = $vMesBase AND mdp_ano = $vAnoBase)
      WHERE bdp_ativo = 1
      AND bdp_contabiliza = 1
      AND bdp_id NOT IN ($idsNotIn)
      GROUP BY bdp_id
      ORDER BY bdp_descricao
    SQL;

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    foreach($arrRs as $rs1){
      $idCategoria  = $rs1["id_categoria"];
      $categoria    = $rs1["categoria"];
      $tipo         = $rs1["tipo"];
      $vlrPrevisto  = ($rs1["previsto"] != "") ? $rs1["previsto"]: 0;
      $vlrRealizado = ($rs1["realizado"] != "") ? $rs1["realizado"]: 0;

      switch ($tipo) {
        case 'V':
          $nameArray = "arrVariaveis";
          break;
        case 'F':
          $nameArray = "arrFixos";
          break;
        case 'I':
          $nameArray = "arrInvestimento";
          break;
        default:
          $nameArray = "";
          break;
      }

      if($nameArray != ""){
        $$nameArray[] = array(
          "idCategoria"           => $idCategoria,
          "categoria"             => $categoria,
          "tipo"                  => $tipo,
          "vlrPrevisto"           => $vlrPrevisto,
          "vlrPrevistoFormatado"  => number_format($vlrPrevisto, 2, ",", "."),
          "vlrRealizado"          => $vlrRealizado,
          "vlrRealizadoFormatado" => number_format($vlrRealizado, 2, ",", "."),
          "excedeuPrevisto"       => ($vlrRealizado > $vlrPrevisto),
        );
      }
    }

    return array(
      "arrVariaveis"    => $arrVariaveis,
      "arrFixos"        => $arrFixos,
      "arrInvestimento" => $arrInvestimento,
    );
  }

  public function getHtmlTotaisGastos($arrFilters=array()){
    // filtros
    $vMesBase   = isset($arrFilters["mesBase"]) ? $arrFilters["mesBase"]: (int) date("m");
    $vAnoBase   = isset($arrFilters["anoBase"]) ? $arrFilters["anoBase"]: (int) date("Y");
    // =======

    $arrRetGastos = $this->getArrCategoriaGastos($vMesBase, $vAnoBase);

    $html  = "<button id='btnGerenciarPrevisao' data-mes='$vMesBase' data-ano='$vAnoBase' style='margin:15px 0;' class='btn btn-info btn-mini'>Gerenciar Previsão</button>";
    $html .= "<div class='row-fluid'>";
    $html .= "  <div class='span4'>";
    $html .= "    <h4>Gastos Fixos</h4>";
    $html .= "    " . $this->getHtmlTbTotais($arrRetGastos["arrFixos"]);
    $html .= "  </div>";
    $html .= "  <div class='span4'>";
    $html .= "    <h4>Gastos Variáveis</h4>";
    $html .= "    " . $this->getHtmlTbTotais($arrRetGastos["arrVariaveis"]);
    $html .= "  </div>";
    $html .= "  <div class='span4'>";
    $html .= "    <h4>Investimentos</h4>";
    $html .= "    " . $this->getHtmlTbTotais($arrRetGastos["arrInvestimento"]);
    $html .= "  </div>";
    $html .= "</div>";

    return $html;
  }

  public function getLancamento($lanId){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";
    $arrRet["arrLancamentoDados"] = array();

    if(!is_numeric($lanId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar a parcela!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("lan_id, lan_despesa, lan_tipo, lan_parcela, lan_compra, lan_vencimento, lan_valor, GROUP_CONCAT(bdp_id) AS categoria, GROUP_CONCAT(bdp_descricao) AS bdp_descricao, lan_pagamento, lan_valor_pago, lan_conta, con_nome, con_sigla, lan_observacao, lan_confirmado");
    $this->db->from("tb_lancamento");
    $this->db->join("tb_lancamento_despesa", "ld_lan_id = lan_id", "left");
    $this->db->join("tb_base_despesa", "bdp_id = ld_bdp_id", "left");
    $this->db->join("tb_conta", "con_id = lan_conta", "left");
    $this->db->where("lan_id", $lanId);
    $this->db->group_by('lan_id');
    $query = $this->db->get();
    $this->db->reset_query();

    if($query->num_rows() > 0){
      $row = $query->row();

      switch ($row->lan_tipo) {
        case 'D':
          $strTipo = "Despesa";
          break;
        case 'R':
          $strTipo = "Receita";
          break;
        case 'T':
            $strTipo = "Transferência";
            break;
        default:
          $strTipo = "";
          break;
      }

      $arrLancamentoDados = [];
      $arrLancamentoDados["lan_id"]         = $row->lan_id;
      $arrLancamentoDados["lan_despesa"]    = $row->lan_despesa;
      $arrLancamentoDados["lan_tipo"]       = $row->lan_tipo;
      $arrLancamentoDados["str_tipo"]       = $strTipo;
      $arrLancamentoDados["lan_parcela"]    = $row->lan_parcela;
      $arrLancamentoDados["lan_compra"]     = $row->lan_compra;
      $arrLancamentoDados["lan_vencimento"] = $row->lan_vencimento;
      $arrLancamentoDados["lan_valor"]      = $row->lan_valor;
      $arrLancamentoDados["categoria"]      = $row->categoria;
      $arrLancamentoDados["bdp_descricao"]  = $row->bdp_descricao;
      $arrLancamentoDados["lan_pagamento"]  = $row->lan_pagamento;
      $arrLancamentoDados["lan_valor_pago"] = $row->lan_valor_pago;
      $arrLancamentoDados["lan_conta"]      = $row->lan_conta;
      $arrLancamentoDados["con_nome"]       = $row->con_nome;
      $arrLancamentoDados["con_sigla"]      = $row->con_sigla;
      $arrLancamentoDados["lan_observacao"] = $row->lan_observacao;
      $arrLancamentoDados["lan_confirmado"] = $row->lan_confirmado;

      $arrLancamentoDados["ld_bdp_id"] = [];
      $arrLancamentoDados["ld_valor"]  = [];

      // categorias
      $this->db->select("ld_bdp_id, ld_valor");
      $this->db->from("tb_lancamento_despesa");
      $this->db->where("ld_lan_id", $row->lan_id);
      $this->db->order_by('ld_id');
      $query2 = $this->db->get();
      foreach ($query2->result() as $row) {
        $arrLancamentoDados["ld_bdp_id"][] = $row->ld_bdp_id;
        $arrLancamentoDados["ld_valor"][]  = $row->ld_valor;
      }

      $arrRet["arrLancamentoDados"] = $arrLancamentoDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrLancamentoDados){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vDespesa = isset($arrLancamentoDados["lan_despesa"]) ? $arrLancamentoDados["lan_despesa"]: "";
    if( strlen($vDespesa) < 3 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe a descrição do lançamento!";
      return $arrRet;
    }

    $vTipo = isset($arrLancamentoDados["lan_tipo"]) ? $arrLancamentoDados["lan_tipo"]: "";
    $arrTiposValidos = array("R", "D", "T");
    if(!in_array($vTipo, $arrTiposValidos)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe o tipo do lançamento!";
      return $arrRet;
    }

    $vVencimento = (isset($arrLancamentoDados["lan_vencimento"])) ? $arrLancamentoDados["lan_vencimento"]: "";
    $isVctoValid = isValidDate($vVencimento, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe uma data de vencimento válida!";
      return $arrRet;
    }

    $vTipo  = (isset($arrLancamentoDados["lan_tipo"])) ? (float)$arrLancamentoDados["lan_tipo"]: "";
    $vValor = (isset($arrLancamentoDados["lan_valor"])) ? (float)$arrLancamentoDados["lan_valor"]: "";
    if(!is_numeric($vValor) || (!$vValor > 0 && $vTipo != "T")){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $vPagamento = (isset($arrLancamentoDados["lan_pagamento"])) ? $arrLancamentoDados["lan_pagamento"]: "";
    if($vPagamento != ""){
      $isVctoValid = isValidDate($vPagamento, "Y-m-d");
      if(!$isVctoValid){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe uma data de pagamento válida!";
        return $arrRet;
      }
    }

    $vValorPg = (isset($arrLancamentoDados["lan_valor_pago"])) ? (float)$arrLancamentoDados["lan_valor_pago"]: "";
    if($vValorPg != ""){
      if(!is_numeric($vValorPg) || (!$vValorPg > 0 && $vTipo != "T")){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe um valor pago válido!";
        return $arrRet;
      }
    }

    $vConta = isset($arrLancamentoDados["lan_conta"]) ? $arrLancamentoDados["lan_conta"]: "";
    if($vConta != ""){
      if(!is_numeric($vConta)){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe a conta do lançamento!";
        return $arrRet;
      }
    }

    $arrCategorias = isset($arrLancamentoDados["ld_bdp_id"]) ? $arrLancamentoDados["ld_bdp_id"]: [];
    if (empty($arrCategorias)) {
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe pelo menos uma categoria!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrLancamentoDados, $repeteMeses=null){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrLancamentoDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->helpers("utils");
    $this->load->model('Tb_Lancamento_Despesa');
    $this->db->trans_start();

    $vDespesa    = isset($arrLancamentoDados["lan_despesa"]) ? $arrLancamentoDados["lan_despesa"]: null;
    $vTipo       = isset($arrLancamentoDados["lan_tipo"]) ? $arrLancamentoDados["lan_tipo"]: null;
    $vParcela    = isset($arrLancamentoDados["lan_parcela"]) && $arrLancamentoDados["lan_parcela"] != "" ? $arrLancamentoDados["lan_parcela"]: null;
    $vCompra     = isset($arrLancamentoDados["lan_compra"]) && strlen($arrLancamentoDados["lan_compra"]) == 10 ? $arrLancamentoDados["lan_compra"]: null;
    $vVencimento = isset($arrLancamentoDados["lan_vencimento"]) && strlen($arrLancamentoDados["lan_vencimento"]) == 10 ? $arrLancamentoDados["lan_vencimento"]: null;
    $vValor      = isset($arrLancamentoDados["lan_valor"]) ? $arrLancamentoDados["lan_valor"]: null;
    $vPagamento  = isset($arrLancamentoDados["lan_pagamento"]) && strlen($arrLancamentoDados["lan_pagamento"]) == 10 ? $arrLancamentoDados["lan_pagamento"]: null;
    $vValorPago  = isset($arrLancamentoDados["lan_valor_pago"]) ? $arrLancamentoDados["lan_valor_pago"]: null;
    $vConta      = isset($arrLancamentoDados["lan_conta"]) && $arrLancamentoDados["lan_conta"] > 0 ? $arrLancamentoDados["lan_conta"]: null;
    $vObservacao = isset($arrLancamentoDados["lan_observacao"]) ? $arrLancamentoDados["lan_observacao"]: null;
    $vConfirmado = isset($arrLancamentoDados["lan_confirmado"]) ? $arrLancamentoDados["lan_confirmado"]: 0;

    $vArrCategorias = isset($arrLancamentoDados["ld_bdp_id"]) ? $arrLancamentoDados["ld_bdp_id"]: [];
    $vArrCategoriasVlr = isset($arrLancamentoDados["ld_valor"]) ? $arrLancamentoDados["ld_valor"]: [];

    $data = array(
      'lan_despesa'    => $vDespesa,
      'lan_tipo'       => $vTipo,
      'lan_parcela'    => $vParcela,
      'lan_compra'     => $vCompra,
      'lan_vencimento' => $vVencimento,
      'lan_valor'      => $vValor,
      'lan_pagamento'  => $vPagamento,
      'lan_valor_pago' => $vValorPago,
      'lan_conta'      => $vConta,
      'lan_observacao' => $vObservacao,
      'lan_confirmado' => $vConfirmado,
    );

    $vezes = (is_numeric($repeteMeses) && $repeteMeses > 0) ? (1 + $repeteMeses): 1;
    $arrData = explode("-", $data["lan_vencimento"]);
    $diaOriginal = str_pad($arrData[2], 2, "0", STR_PAD_LEFT);
    $lastInsertdId = 0;

    for($i=1; $i<=$vezes;$i++){
      if($vezes > 1){
        $data["lan_parcela"] = "$i de $vezes";
      }

      $this->db->insert('tb_lancamento', $data);
      $lastInsertdId = $this->db->insert_id();

      // insere categorias
      for ($i=0; $i < count($vArrCategorias); $i++) {
        if (!isset($vArrCategorias[$i]) || !isset($vArrCategoriasVlr[$i])) {
          continue;
        }

        $this->Tb_Lancamento_Despesa->insert([
          'ld_lan_id' => $lastInsertdId,
          'ld_bdp_id' => $vArrCategorias[$i],
          'ld_valor'  => $vArrCategoriasVlr[$i],
        ]);
      }

      // configura proximo vencimento
      $arrData = explode("-", $data["lan_vencimento"]);
      $dia     = $diaOriginal;
      $mes     = $arrData[1] + 1;
      $ano     = $arrData[0];

      if($mes > 12){
        $mes = 1;
        $ano++;
      }

      $dia = str_pad($dia, 2, "0", STR_PAD_LEFT);
      $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);

      $novaData = $ano . "-" . $mes . "-" . $dia;
      $validDate = isValidDate($novaData, 'Y-m-d');
      while($validDate == false){
        $novaData = date('Y-m-t', strtotime($ano . "-" . $mes . "-01"));
        $validDate = isValidDate($novaData, 'Y-m-d');
      }

      $data["lan_vencimento"] = $novaData;
      // ============================
    }

    $this->db->trans_complete();
    $retInsert = $this->db->trans_status();
    if(!$retInsert){
      $arrRet["lan_id"] = null;
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["lan_id"] = $lastInsertdId;
      $arrRet["erro"] = false;
      $arrRet["msg"] = "Lançamento inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrLancamentoDados){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vLanId = (isset($arrLancamentoDados["lan_id"])) ? $arrLancamentoDados["lan_id"]: "";
    if(!$vLanId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para editar o Lançamento!";
      return $arrRet;
    }

    $vDespesa = isset($arrLancamentoDados["lan_despesa"]) ? $arrLancamentoDados["lan_despesa"]: "";
    if( strlen($vDespesa) < 3 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe a descrição do lançamento!";
      return $arrRet;
    }

    $vTipo = isset($arrLancamentoDados["lan_tipo"]) ? $arrLancamentoDados["lan_tipo"]: "";
    $arrTiposValidos = array("R", "D", "T");
    if(!in_array($vTipo, $arrTiposValidos)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe o tipo do lançamento!";
      return $arrRet;
    }

    $vVencimento = (isset($arrLancamentoDados["lan_vencimento"])) ? $arrLancamentoDados["lan_vencimento"]: "";
    $isVctoValid = isValidDate($vVencimento, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe uma data de vencimento válida!";
      return $arrRet;
    }

    $vValor = (isset($arrLancamentoDados["lan_valor"])) ? (float)$arrLancamentoDados["lan_valor"]: "";
    if(!is_numeric($vValor) || !$vValor > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $vPagamento = (isset($arrLancamentoDados["lan_pagamento"])) ? $arrLancamentoDados["lan_pagamento"]: "";
    if($vPagamento != ""){
      $isVctoValid = isValidDate($vPagamento, "Y-m-d");
      if(!$isVctoValid){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe uma data de pagamento válida!";
        return $arrRet;
      }
    }

    $vValorPg = (isset($arrLancamentoDados["lan_valor_pago"])) ? (float)$arrLancamentoDados["lan_valor_pago"]: "";
    if($vValorPg != ""){
      if(!is_numeric($vValorPg) || $vValorPg < 0){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe um valor pago válido!";
        return $arrRet;
      }
    }

    $vConta = isset($arrLancamentoDados["lan_conta"]) ? $arrLancamentoDados["lan_conta"]: "";
    if($vConta != ""){
      if(!is_numeric($vConta)){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Por favor, informe a conta do lançamento!";
        return $arrRet;
      }
    }

    $arrCategorias = isset($arrLancamentoDados["ld_bdp_id"]) ? $arrLancamentoDados["ld_bdp_id"]: [];
    if (empty($arrCategorias)) {
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe pelo menos uma categoria!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrLancamentoDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrLancamentoDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();
    $this->load->model('Tb_Lancamento_Despesa');

    $vLanId      = (isset($arrLancamentoDados["lan_id"])) ? $arrLancamentoDados["lan_id"]: "";
    $vDespesa    = isset($arrLancamentoDados["lan_despesa"]) ? $arrLancamentoDados["lan_despesa"]: null;
    $vTipo       = isset($arrLancamentoDados["lan_tipo"]) ? $arrLancamentoDados["lan_tipo"]: null;
    $vParcela    = isset($arrLancamentoDados["lan_parcela"]) && $arrLancamentoDados["lan_parcela"] != "" ? $arrLancamentoDados["lan_parcela"]: null;
    $vCompra     = isset($arrLancamentoDados["lan_compra"]) && strlen($arrLancamentoDados["lan_compra"]) == 10 ? $arrLancamentoDados["lan_compra"]: null;
    $vVencimento = isset($arrLancamentoDados["lan_vencimento"]) && strlen($arrLancamentoDados["lan_vencimento"]) == 10 ? $arrLancamentoDados["lan_vencimento"]: null;
    $vValor      = isset($arrLancamentoDados["lan_valor"]) && $arrLancamentoDados["lan_valor"] > 0 ? $arrLancamentoDados["lan_valor"]: null;
    $vPagamento  = isset($arrLancamentoDados["lan_pagamento"]) && strlen($arrLancamentoDados["lan_pagamento"]) == 10 ? $arrLancamentoDados["lan_pagamento"]: null;
    $vValorPago  = isset($arrLancamentoDados["lan_valor_pago"]) && $arrLancamentoDados["lan_valor_pago"] >= 0 ? $arrLancamentoDados["lan_valor_pago"]: null;
    $vConta      = isset($arrLancamentoDados["lan_conta"]) && $arrLancamentoDados["lan_conta"] > 0 ? $arrLancamentoDados["lan_conta"]: null;
    $vObservacao = isset($arrLancamentoDados["lan_observacao"]) ? $arrLancamentoDados["lan_observacao"]: null;
    $vConfirmado = isset($arrLancamentoDados["lan_confirmado"]) ? $arrLancamentoDados["lan_confirmado"]: 0;

    $vArrCategorias = isset($arrLancamentoDados["ld_bdp_id"]) ? $arrLancamentoDados["ld_bdp_id"]: [];
    $vArrCategoriasVlr = isset($arrLancamentoDados["ld_valor"]) ? $arrLancamentoDados["ld_valor"]: [];

    $Lancamento = [];
    $Lancamento["lan_id"]         = $vLanId;
    $Lancamento["lan_despesa"]    = $vDespesa;
    $Lancamento["lan_tipo"]       = $vTipo;
    $Lancamento["lan_parcela"]    = $vParcela;
    $Lancamento["lan_compra"]     = $vCompra;
    $Lancamento["lan_vencimento"] = $vVencimento;
    $Lancamento["lan_valor"]      = $vValor;
    $Lancamento["lan_pagamento"]  = $vPagamento;
    $Lancamento["lan_valor_pago"] = $vValorPago;
    $Lancamento["lan_conta"]      = $vConta;
    $Lancamento["lan_observacao"] = $vObservacao;
    $Lancamento["lan_confirmado"] = $vConfirmado;

    $this->db->where('lan_id', $vLanId);
    $retInsert = $this->db->update('tb_lancamento', $Lancamento);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {

      // deleta categorias
      $this->db->reset_query();
      $this->db->where('ld_lan_id', $vLanId);
      $this->db->delete('tb_lancamento_despesa');

      // insere categorias
      for ($i=0; $i < count($vArrCategorias); $i++) {
        if (!isset($vArrCategorias[$i]) || !isset($vArrCategoriasVlr[$i])) {
          continue;
        }

        $this->Tb_Lancamento_Despesa->insert([
          'ld_lan_id' => $vLanId,
          'ld_bdp_id' => $vArrCategorias[$i],
          'ld_valor'  => $vArrCategoriasVlr[$i],
        ]);
      }

      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Lancamento editado com sucesso!";
    }

    return $arrRet;
  }

  public function delete($lanId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($lanId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();

      // deleta categorias
      $this->db->where('ld_lan_id', $lanId);
      $this->db->delete('tb_lancamento_despesa');

      $this->db->reset_query();
      $this->db->where('lan_id', $lanId);
      $retDelete = $this->db->delete('tb_lancamento');

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Lançamento deletado com sucesso!";
      }

      return $arrRet;
    }
  }

  public function insertTransferencia($valor, $vencimento, $contaDe, $contaPara){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVencimento = (isset($vencimento)) ? $vencimento: "";
    $isVctoValid = isValidDate($vVencimento, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe uma data de vencimento válida!";
      return $arrRet;
    }

    $vValor = (isset($valor)) ? (float)$valor: "";
    if(!is_numeric($vValor) || !$vValor > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $vContaDe = isset($contaDe) ? $contaDe: "";
    if(!is_numeric($vContaDe)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe a conta de origem (DE)!";
      return $arrRet;
    }

    $vContaPara = isset($contaPara) ? $contaPara: "";
    if(!is_numeric($vContaPara)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe a conta de destino (PARA)!";
      return $arrRet;
    }

    if($vContaDe == $vContaPara){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe contas diferentes!";
      return $arrRet;
    }

    $this->load->model("Tb_Lancamento_Despesa");
    $this->load->model("Tb_Conta");
    $retContaDe  = $this->Tb_Conta->getConta($vContaDe);
    $ContaDescDe = ($retContaDe["erro"]) ? $vContaDe: $retContaDe["arrContaDados"]["con_sigla"];

    $retContaPara  = $this->Tb_Conta->getConta($vContaPara);
    $ContaDescPara = ($retContaPara["erro"]) ? $vContaPara: $retContaPara["arrContaDados"]["con_sigla"];

    // lancamento DE
    $LancamentoDe = [];
    $LancamentoDe["lan_despesa"]    = "Transf. De $ContaDescDe";
    $LancamentoDe["lan_tipo"]       = "T";
    $LancamentoDe["lan_vencimento"] = $vVencimento;
    $LancamentoDe["lan_valor"]      = $vValor;
    $LancamentoDe["lan_pagamento"]  = $vVencimento;
    $LancamentoDe["lan_valor_pago"] = $vValor;
    $LancamentoDe["lan_conta"]      = $vContaPara;
    $LancamentoDe["lan_observacao"] = "";
    $LancamentoDe["ld_bdp_id"]      = [40];
    $LancamentoDe["ld_valor"]       = [$LancamentoDe["lan_valor"]];

    $this->insert($LancamentoDe);
    // =============

    // lancamento PARA
    $LancamentoPara = [];
    $LancamentoPara["lan_despesa"]    = "Transf. Para $ContaDescPara";
    $LancamentoPara["lan_tipo"]       = "T";
    $LancamentoPara["lan_vencimento"] = $vVencimento;
    $LancamentoPara["lan_valor"]      = "-" . $vValor;
    $LancamentoPara["lan_pagamento"]  = $vVencimento;
    $LancamentoPara["lan_valor_pago"] = "-" . $vValor;
    $LancamentoPara["lan_conta"]      = $vContaDe;
    $LancamentoPara["lan_observacao"] = "";
    $LancamentoPara["ld_bdp_id"]      = [40];
    $LancamentoPara["ld_valor"]       = [$LancamentoPara["lan_valor"]];

    $this->insert($LancamentoPara);
    // ===============

    $arrRet["erro"] = false;
    $arrRet["msg"] = "";
    return $arrRet;
  }

  public function restGetLancamento($lanId){
    $retLancamento = $this->getLancamento($lanId);
    return $retLancamento;
  }

  public function restAddLancamento($arrLancamentoDados, $repeteMeses=null){
    $retLancamento = $this->insert($arrLancamentoDados, $repeteMeses);
    return $retLancamento;
  }

  public function restEditLancamento($arrLancamentoDados){
    $retLancamento = $this->edit($arrLancamentoDados);
    return $retLancamento;
  }

  public function restDeleteLancamento($lanId){
    $retLancamento = $this->delete($lanId);
    return $retLancamento;
  }

  public function restFcmNotifContasPagar($data){
    $this->load->database();

    $sql = "SELECT COUNT(*) AS qt_contas
            		,SUM(lan_valor) AS tot_contas
            FROM tb_lancamento
            WHERE lan_tipo = 'D'
            AND lan_pagamento IS NULL
            AND lan_valor_pago IS NULL
            AND lan_vencimento = '$data'";
    $query = $this->db->query($sql);
    $arrRs = $query->row();

    if(!isset($arrRs)){
      return false;
    } else {
      $dataFormat = date("d/m/Y", strtotime($data));
      $qtContas   = $arrRs->qt_contas;
      $totContas  = CURRENCY_SYMBOL . number_format($arrRs->tot_contas, 2, ",", ".");

      $strRet     = "Você tem $qtContas contas que vencem no dia $dataFormat, no total de $totContas.";
      return $strRet;
    }
  }

  public function getRelDespesasMesData($dtIni, $dtFim)
  {
    if (strlen($dtIni) !== 10 || strlen($dtFim) !== 10) {
        return [];
    }

    $this->load->database();
    $sql = <<<SQL
        SELECT lan_id
            ,lan_despesa
            ,lan_parcela
            ,lan_compra
            ,lan_vencimento
            ,ld_valor
            ,lan_valor
            ,bdp_descricao
            ,lan_pagamento
            ,lan_valor_pago
            ,con_nome
            ,lan_observacao
        FROM tb_lancamento
        LEFT JOIN tb_conta ON (con_id = lan_conta)
        LEFT JOIN tb_lancamento_despesa ON (ld_lan_id = lan_id)
        LEFT JOIN tb_base_despesa ON (bdp_id = ld_bdp_id)
        WHERE lan_tipo = 'D'
        AND COALESCE(lan_compra, lan_vencimento) BETWEEN '$dtIni' AND '$dtFim'
        ORDER BY bdp_descricao, COALESCE(lan_compra, lan_vencimento)
    SQL;
    $query = $this->db->query($sql);
    return $query->result_array();
  }
}