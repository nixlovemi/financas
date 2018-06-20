<?php
$htmlContasPagarTable = isset($htmlContasPagarTable) ? $htmlContasPagarTable: "";
?>

<h1>Pagamentos</h1>

<a class="btn btn-info btn-large" href="javascript:;" id="btnJsonAddContaPagar">NOVO PAGAMENTO</a>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="accordion-group widget-box">
  <div class="accordion-heading">
    <div class="widget-title">
      <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse" class="collapsed">
        <span class="icon"><i class="icon-filter"></i></span>
        <h5>Filtros</h5>
      </a>
    </div>
  </div>
  <div class="accordion-body collapse" id="collapseGOne" style="height: 0px;">
    <div class="widget-content nopadding">
      <form id="frmFiltrosPagamentos" class="form-horizontal">
        <div class="control-group">
          <label class="control-label">Vencimento</label>
          <div class="controls">
            <input value="" class="span3 mask_datepicker" type="text" id="filterDtVctoIni" name="filterDtVctoIni" placeholder="Vencimento Inicial" />
            &nbsp; até &nbsp;
            <input value="" class="span3 mask_datepicker" type="text" id="filterDtVctoFim" name="filterDtVctoFim" placeholder="Vencimento Final" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Pagamento</label>
          <div class="controls">
            <input value="" class="span3 mask_datepicker" type="text" id="filterDtPgtoIni" name="filterDtPgtoIni" placeholder="Pagamento Inicial" />
            &nbsp; até &nbsp;
            <input value="" class="span3 mask_datepicker" type="text" id="filterDtPgtoFim" name="filterDtPgtoFim" placeholder="Pagamento Final" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Exibir</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterApenasPagas' id='filterApenasPagas'>
              <option value=''>Todas os Pagamentos</option>
              <option value='S'>Apenas Pagos</option>
              <option value='N'>Apenas não Pagos</option>
            </select>
          </div>
        </div>
        <div class="control-group" style="padding:8px 0;">
          <center>
            <a class="btn btn-info" href="javascript:;" id="btnFiltrarPagamentos">FILTRAR</a>
          </center>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-money"></i></span>
    <h5>Lista de pagamentos</h5>
  </div>
  <div class="widget-content nopadding" id="dvhtmlContasPagarTable">
    <?php
    echo $htmlContasPagarTable;
    ?>
  </div>
</div>
