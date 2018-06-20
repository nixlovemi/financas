<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Novo Pagamento";

$ContPagar = isset($ContPagar) ? $ContPagar : array();
$detalhes  = isset($detalhes) ? $detalhes: false;
$editar    = isset($editar) ? $editar: false;

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$vCtpId         = isset($ContPagar["ctp_id"]) ? $ContPagar["ctp_id"]: "";
$vCtpVencimento = isset($ContPagar["ctp_dtvencimento"]) && strlen($ContPagar["ctp_dtvencimento"]) == 10 ? date("d/m/Y", strtotime($ContPagar["ctp_dtvencimento"])): "";
$vCtpValor      = isset($ContPagar["ctp_valor"]) ? number_format($ContPagar["ctp_valor"], 2, ",", ""): "";
$vCtpPagamento  = isset($ContPagar["ctp_dtpagamento"]) && strlen($ContPagar["ctp_dtpagamento"]) == 10 ? date("d/m/Y", strtotime($ContPagar["ctp_dtpagamento"])): "";
$vCtpValorPago  = isset($ContPagar["ctp_valor_pago"]) ? number_format($ContPagar["ctp_valor_pago"], 2, ",", ""): "";
$vCtpFornecedor = isset($ContPagar["ctp_fornecedor"]) ? $ContPagar["ctp_fornecedor"]: "";
$vCtpObs        = isset($ContPagar["ctp_obs"]) ? $ContPagar["ctp_obs"]: "";

if($editar){
  $h1Title    = "Editar Pagamento";
}
?>

<div class="container-fluid">
  <h1><?php echo $h1Title; ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Pagamento</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmJsonAddContaPagar" class="form-horizontal form-validation" method="post" action="">
            <?php
            if($detalhes || $editar){
              ?>
              <div class="control-group">
                <label class="control-label">ID</label>
                <div class="controls">
                  <input readonly class="span10" type="text" name="ctpId" id="ctpId" value="<?php echo $vCtpId; ?>" />
                </div>
              </div>
              <?php
            }
            ?>
            <div class="control-group">
              <label class="control-label">Vencimento</label>
              <div class="controls">
                <input value="<?php echo $vCtpVencimento; ?>" class="span10 mask_datepicker" type="text" id="ctpDtvencimento" name="ctpDtvencimento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="ctpValor" id="ctpValor" value="<?php echo $vCtpValor; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Pagamento</label>
              <div class="controls">
                <input value="<?php echo $vCtpPagamento; ?>" class="span10 mask_datepicker" type="text" id="ctpDtpagamento" name="ctpDtpagamento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor Pago</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="ctpValorPago" id="ctpValorPago" value="<?php echo $vCtpValorPago; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Fornecedor</label>
              <div class="controls">
                <input <?php echo $strReadyonly; ?> class="span10" type="text" name="ctpFornecedor" id="ctpFornecedor" value="<?php echo $vCtpFornecedor; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Observação</label>
              <div class="controls">
                <textarea <?php echo $strReadyonly; ?> class="span10" name="ctpObservacao" id="ctpObservacao"><?php echo $vCtpObs; ?></textarea>
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
