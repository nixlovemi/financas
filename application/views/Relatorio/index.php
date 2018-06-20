<h1>Relatórios</h1>

<?php
$arrRelatorios = [];
$arrRelatorios["Fluxo de Caixa"] = [];
$arrRelatorios["Fluxo de Caixa"]["controller"] = "Relatorio";
$arrRelatorios["Fluxo de Caixa"]["action"]     = "abreRelFluxoCx";

if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>Escolha:</h5>
  </div>
  <div class="widget-content nopadding">
    <form id="frmRelatorios" class="form-horizontal form-validation" method="post" action="">
      <div class="control-group">
        <label class="control-label">&nbsp;</label>
        <div class="controls">
          <select id="cbRelatorios" style="width: 70%;">
            <option value=""></option>

            <?php
            foreach($arrRelatorios as $txtRelatorio => $arrInfo){
                $controller = $arrInfo["controller"];
                $action     = $arrInfo["action"];

                echo "<option value='$controller@$action'>$txtRelatorio</option>";
            }
            ?>
          </select>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="dvOpenRelatorio"></div>
