<?php
$rsFluxo = isset($rsFluxo) ? $rsFluxo: array();

$htmlTable  = "";
$htmlTable .= "<table class='table table-bordered' id='' style='margin-bottom:20px;'>";
$htmlTable .= "  <thead>";
$htmlTable .= "    <tr>";
$htmlTable .= "      <th width='10%'>Dia</th>";
$htmlTable .= "      <th width='18%'>Contas a Receber</th>";
$htmlTable .= "      <th width='18%'>Contas a Pagar</th>";
$htmlTable .= "      <th width='18%'>Saldo Inicial</th>";
$htmlTable .= "      <th width='18%'>Saldo do Dia</th>";
$htmlTable .= "      <th width='18%'>Acumulado</th>";
$htmlTable .= "    </tr>";
$htmlTable .= "  </thead>";
$htmlTable .= "  <tbody>";

foreach($rsFluxo as $diaAtual => $row){
  $dia       = $row["dia"];
  $receber   = "R$" . number_format($row["receber"], 2, ",", ".");
  $pagar     = "R$" . number_format($row["pagar"], 2, ",", ".");
  $saldoIni  = ($row["saldo_inicial"] > 0) ? "R$" . number_format($row["saldo_inicial"], 2, ",", "."): "";
  $saldoDia  = "R$" . number_format($row["saldo_dia"], 2, ",", ".");
  $acumulado = "R$" . number_format($row["acumulado"], 2, ",", ".");

  $cssSaldoDia  = ($row["saldo_dia"] < 0) ? "red": "blue";
  $cssAcumulado = ($row["acumulado"] < 0) ? "red": "blue";

  $htmlTable .= "<tr>";
  $htmlTable .= "  <td>$dia</td>";
  $htmlTable .= "  <td><a style='color:blue;' href='javascript:;' id='opnDetRelFluxoCx' data-tipo='CR' data-dia='$diaAtual'>$receber</a></td>";
  $htmlTable .= "  <td><a style='color:red;' href='javascript:;' id='opnDetRelFluxoCx' data-tipo='CP' data-dia='$diaAtual'>$pagar</a></td>";
  $htmlTable .= "  <td>$saldoIni</td>";
  $htmlTable .= "  <td style='color:$cssSaldoDia;'>$saldoDia</td>";
  $htmlTable .= "  <td style='color:$cssAcumulado;'>$acumulado</td>";
  $htmlTable .= "</tr>";
}

$htmlTable .= "  </tbody>";
$htmlTable .= "</table>";
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>RESULTADO</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlTable;
    ?>
    <center>
      <?php
      $jsonStrArrRel = json_encode($rsFluxo);
      ?>

      <form target="_blank" method="post" action="<?php echo base_url(); ?>Relatorio/pdfRelFluxoCx">
        <input type="hidden" name="jsonRelFluxoCx" value="<?php echo base64url_encode($jsonStrArrRel); ?>" />
        <button onClick="this.form.submit();" style="margin-bottom:20px;" type="button" class="btn btn-danger">GERAR PDF</button>
      </form>
    </center>
  </div>
</div>
