<?php
$mesBase = isset($mes_base) ? str_pad($mes_base, 2, "0", STR_PAD_LEFT): "";
$anoBase = isset($ano_base) ? $ano_base: "";

$arrRetGastos    = isset($arrRetGastos) ? $arrRetGastos: array();
$arrVariaveis    = isset($arrRetGastos["arrVariaveis"]) ? $arrRetGastos["arrVariaveis"]: array();
$arrFixos        = isset($arrRetGastos["arrFixos"]) ? $arrRetGastos["arrFixos"]: array();
$arrInvestimento = isset($arrRetGastos["arrInvestimento"]) ? $arrRetGastos["arrInvestimento"]: array();
?>

<div class="container-fluid">
  <h1>Previsões - <?php echo $mesBase . "/" . $anoBase ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Valores</h5>
        </div>
        <div class="widget-content">
          <form name="frmJsonCadPrevisao" id="frmJsonCadPrevisao" method="post">
            <input type="hidden" name="mes_base" value="<?php echo (int)$mesBase; ?>" />
            <input type="hidden" name="ano_base" value="<?php echo (int)$anoBase; ?>" />

            <?php
            $arrLoop = [];
            $arrLoop["Fixos"]        = $arrFixos;
            $arrLoop["Variáveis"]    = $arrVariaveis;
            $arrLoop["Investimento"] = $arrInvestimento;

            $arrTotaisRealiz = [];
            $arrTotaisRealiz["Fixos"]        = 0;
            $arrTotaisRealiz["Variáveis"]    = 0;
            $arrTotaisRealiz["Investimento"] = 0;

            foreach($arrLoop as $titulo => $arrayInfo){
              ?>
              <div class="row-fluid nopadding" style="margin-top: 0px;">
                <h4><?php echo $titulo; ?></h4>
                <table class='table tableBordered' id=''>
                  <thead>
                    <tr>
                      <td>Categoria</td>
                      <td width="18%">Previsto</td>
                      <td width="18%">Realizado</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $html = "";
                    foreach($arrayInfo as $rs1){
                      $previsto  = ($rs1["vlrPrevisto"] != "") ? $rs1["vlrPrevisto"]: 0;
                      $realizado = ($rs1["vlrRealizado"] != "") ? $rs1["vlrRealizado"]: 0;

                      $arrTotaisRealiz[$titulo] += $realizado;

                      $idCategoria  = $rs1["idCategoria"];
                      $categoria    = $rs1["categoria"];
                      $vlrPrevisto  = CURRENCY_SYMBOL.number_format($previsto, 2, ",", ".");
                      $vlrRealizado = CURRENCY_SYMBOL.number_format($realizado, 2, ",", ".");

                      $html .= "<tr>";
                      $html .= "  <td>$categoria</td>";
                      $html .= "  <td><input data-id='$idCategoria' class='span9 mask_moeda $titulo' type='text' name='lanValor_$idCategoria' id='lanValor_$idCategoria' value='$previsto' /></td>";
                      $html .= "  <td>$vlrRealizado</td>";
                      $html .= "</tr>";
                    }
                    echo $html;
                    ?>
                  </tbody>
                </table>
                <div id="total_<?php echo $titulo; ?>" class="pull-right">
                  <h4></h4>
                </div>
              </div>
              <?php
            }
            ?>
            <div class="row-fluid nopadding" style="margin-top: 20px;">
              <h4>TOTAIS</h4>
              <table class='table tableBordered' id=''>
                <thead>
                  <tr>
                    <td>Categoria</td>
                    <td width="18%">Previsto</td>
                    <td width="18%">Realizado</td>
                    <td width="18%">Diferença</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($arrLoop as $titulo => $arrayInfo){
                    ?>
                    <tr>
                      <td><?php echo $titulo; ?></td>
                      <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totaisPrev<?php echo $titulo; ?>"></span></td>
                      <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totaisRealiz<?php echo $titulo; ?>"><?php echo number_format($arrTotaisRealiz[$titulo], 2, ",", "."); ?></span></td>
                      <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totaisDif<?php echo $titulo; ?>"></span></td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td><b>TOTAIS</b></td>
                    <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totGeralPrev"></span></td>
                    <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totGeralRealiz"></span></td>
                    <td width="18%"><?=CURRENCY_SYMBOL?> <span id="totGeralDif"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
