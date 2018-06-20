<?php
$htmlContaRecebTable = isset($htmlContaRecebTable) ? $htmlContaRecebTable: "";
$arrClientes         = isset($arrClientes) ? $arrClientes: array();
$arrVendedores       = isset($arrVendedores) ? $arrVendedores: array();
?>

<h1>Recebimentos</h1>

<a class="btn btn-info btn-large" href="javascript:;" id="btnJsonAddContaReceb">NOVO RECEBIMENTO</a>

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
      <form id="frmFiltrosRecebimentos" class="form-horizontal">
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
          <label class="control-label">Cliente</label>
          <div class="controls">
            <?php
            echo "<select class='span6 m-wrap' name='filterCliente' id='filterCliente'>";
            echo "<option value=''></option>";
            foreach($arrClientes as $Cliente){
              $cliId    = $Cliente["cli_id"];
              $cliNome  = $Cliente["cli_nome"];
              $selected = ($vVdaCliId == $cliId) ? " selected ": "";

              echo "<option $selected value='$cliId'>$cliNome</option>";
            }
            echo "</select>";
            ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Vendedor</label>
          <div class="controls">
            <?php
            echo "<select class='span6 m-wrap' name='filterVendedor' id='filterVendedor'>";
            echo "<option value=''></option>";
            foreach($arrVendedores as $Vendedor){
              $venId    = $Vendedor["ven_id"];
              $venNome  = $Vendedor["ven_nome"];
              $selected = ($vVdaVenId == $venId) ? " selected ": "";

              echo "<option $selected value='$venId'>$venNome</option>";
            }
            echo "</select>";
            ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Exibir</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterApenasPagas' id='filterApenasPagas'>
              <option value=''>Todas os Recebimentos</option>
              <option value='S'>Apenas Pagos</option>
              <option value='N'>Apenas não Pagos</option>
            </select>
          </div>
        </div>
        <div class="control-group" style="padding:8px 0;">
          <center>
            <a class="btn btn-info" href="javascript:;" id="btnFiltrarRecebimentos">FILTRAR</a>
          </center>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-money"></i></span>
    <h5>Lista de recebimentos</h5>
  </div>
  <div class="widget-content nopadding" id="dvHtmlContaRecebTable">
    <?php
    echo $htmlContaRecebTable;
    ?>
  </div>
</div>
