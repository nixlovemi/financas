<?php
$strLanIds = (isset($strLanIds) && $strLanIds != "") ? $strLanIds: "";
$arrContas = (isset($arrContas)) ? $arrContas: array();
$htmlLcto  = (isset($htmlLcto)) ? $htmlLcto: "";
?>

<div class="container-fluid">
  <h1>Dar Baixa em Grupo</h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Info</h5>
        </div>
        <div class="widget-content">
          <form class="form-horizontal form-validation" name="frmJsonPostBaixaGrupo" id="frmJsonPostBaixaGrupo" method="post">
            <input type="hidden" name="lan_ids" value="<?php echo $strLanIds; ?>" />

            <div class="control-group">
              <label class="control-label">Pagamento</label>
              <div class="controls">
                <input value="" class="span10 mask_datepicker" type="text" id="lanPagamento" name="lanPagamento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Conta</label>
              <div class="controls">
                <?php
                echo "<select class='span10' name='lanConta' id='lanConta'>";
                echo "<option value=''></option>";
                foreach($arrContas as $conta){
                  $conId    = $conta["con_id"];
                  $conDesc  = "[" . $conta["con_sigla"] . "] " . $conta["con_nome"];

                  echo "<option value='$conId'>$conDesc</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <?php echo $htmlLcto; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
