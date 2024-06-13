<?php
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>RELATÓRIO DE FLUXO DE CAIXA</h5>
  </div>
  <div class="widget-content nopadding">
    <form id="frmInfoRelFluxoCx" class="form-horizontal form-validation" method="post" action="">
      <div class="control-group">
        <label class="control-label">Período</label>
        <div class="controls">
          <input placeholder="Data Inicial" value="" class="mask_datepicker span3 m-wrap" type="text" id="cxaDataIni" name="cxaDataIni" />
          <input placeholder="Data Final" value="" class="mask_datepicker span3 m-wrap" type="text" id="cxaDataFim" name="cxaDataFim" />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Saldo Inicial</label>
        <div class="controls">
          <input placeholder="Data Saldo" value="<?php echo date('d/m/Y'); ?>" class="mask_datepicker span3 m-wrap" type="text" id="cxaDataSaldo" name="cxaDataSaldo" />
          <div class="input-prepend">
            <span class="add-on"><?=CURRENCY_SYMBOL?></span>
            <input placeholder="Valor Saldo" value="0,00" class="mask_moeda span3 m-wrap" type="text" id="cxaVlrSaldo" name="cxaVlrSaldo" />
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button id="postRelFluxoCx" type="button" class="btn btn-success">GERAR RELATÓRIO</button>
      </div>
    </form>
  </div>
</div>

<div id="dvPostRelFluxoCx"></div>
