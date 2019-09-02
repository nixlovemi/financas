<?php
$strLanIds = (isset($strLanIds) && $strLanIds != "") ? $strLanIds: "";
$arrContas = (isset($arrContas)) ? $arrContas: array();
$htmlLcto  = (isset($htmlLcto)) ? $htmlLcto: "";
?>

<div class="container-fluid">
  <h1>Deletar Lançamentos</h1>

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
              <?php echo $htmlLcto; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
