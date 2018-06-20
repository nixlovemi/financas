<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Nova Conta a Receber";

$ContReceber   = isset($ContReceber) ? $ContReceber : array();
$detalhes      = isset($detalhes) ? $detalhes: false;
$editar        = isset($editar) ? $editar: false;
$arrClientes   = (isset($arrClientes)) ? $arrClientes: array();

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$vCtrId         = isset($ContReceber["ctr_id"]) ? $ContReceber["ctr_id"]: "";
$vCtrCliId      = isset($ContReceber["ctr_cli_id"]) ? $ContReceber["ctr_cli_id"]: "";
$vCtrVenId      = isset($ContReceber["ctr_ven_id"]) ? $ContReceber["ctr_ven_id"]: "";
$vCtrVencimento = isset($ContReceber["ctr_dtvencimento"]) && strlen($ContReceber["ctr_dtvencimento"]) == 10 ? date("d/m/Y", strtotime($ContReceber["ctr_dtvencimento"])): "";
$vCtrValor      = isset($ContReceber["ctr_valor"]) ? number_format($ContReceber["ctr_valor"], 2, ",", ""): "";
$vCtrPagamento  = isset($ContReceber["ctr_dtpagamento"]) && strlen($ContReceber["ctr_dtpagamento"]) == 10 ? date("d/m/Y", strtotime($ContReceber["ctr_dtpagamento"])): "";
$vCtrValorPago  = isset($ContReceber["ctr_valor_pago"]) ? number_format($ContReceber["ctr_valor_pago"], 2, ",", ""): "";
$vCtrObs        = isset($ContReceber["ctr_obs"]) ? $ContReceber["ctr_obs"]: "";

if($editar){
  $h1Title    = "Editar Conta a Receber";
}
?>

<div class="container-fluid">
  <h1><?php echo $h1Title; ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Conta a Receber</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmJsonAddContaReceb" class="form-horizontal form-validation" method="post" action="">
            <?php
            if($detalhes || $editar){
              ?>
              <div class="control-group">
                <label class="control-label">ID</label>
                <div class="controls">
                  <input readonly class="span10" type="text" name="ctrId" id="ctrId" value="<?php echo $vCtrId; ?>" />
                </div>
              </div>
              <?php
            }
            ?>
            <div class="control-group">
              <label class="control-label">Cliente</label>
              <div class="controls">
                <!-- validate-required -->
                <?php
                echo "<select $strDisabled class='span10 m-wrap' name='ctrCliente' id='ctrCliente'>";
                echo "<option value=''></option>";
                foreach($arrClientes as $Cliente){
                  $cliId    = $Cliente["cli_id"];
                  $cliNome  = $Cliente["cli_nome"];
                  $selected = ($vCtrCliId == $cliId) ? " selected ": "";

                  echo "<option $selected value='$cliId'>$cliNome</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Vencimento</label>
              <div class="controls">
                <input value="<?php echo $vCtrVencimento; ?>" class="span10 mask_datepicker" type="text" id="ctrDtvencimento" name="ctrDtvencimento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="ctrValor" id="ctrValor" value="<?php echo $vCtrValor; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Pagamento</label>
              <div class="controls">
                <input value="<?php echo $vCtrPagamento; ?>" class="span10 mask_datepicker" type="text" id="ctrDtpagamento" name="ctrDtpagamento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor Pago</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="ctrValorPago" id="ctrValorPago" value="<?php echo $vCtrValorPago; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Observação</label>
              <div class="controls">
                <textarea <?php echo $strReadyonly; ?> class="span10" name="ctrObservacao" id="ctrObservacao"><?php echo $vCtrObs; ?></textarea>
              </div>
            </div>
            <div class="form-actions">
              <?php
              foreach($arrButtons as $button){
                echo $button;
              }
              ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
