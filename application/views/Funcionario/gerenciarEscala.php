<?php
$arrFuncionario = (isset($arrFuncionario)) ? $arrFuncionario: array();

$dtIniMes = "01" . date("/m/Y");
$dtFimMes = date("t/m/Y");
?>

<div class="container-fluid">
  <h1>Gerenciar Escalas</h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-briefcase"></i> </span>
          <h5>Dados</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmShowEscalaFunc" class="form-horizontal form-validation" method="post" action="">
            <div class="control-group">
              <label class="control-label">Funcionário</label>
              <div class="controls controls-row">
                <?php
                echo "<select class='span8 m-wrap' name='ge_funId' id='ge_funId'>";
                echo "<option value=''></option>";
                foreach($arrFuncionario as $Funcionario){
                  $funId   = $Funcionario["fun_id"];
                  $funNome = $Funcionario["fun_nome"];

                  echo "<option value='$funId'>$funNome</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Período</label>
              <div class="controls">
                <input placeholder="Data início" value="<?php echo $dtIniMes; ?>" class="span4 mask_datepicker" type="text" id="ge_periodoIni" name="ge_periodoIni" />
                <input placeholder="Data fim" value="<?php echo $dtFimMes; ?>" class="span4 mask_datepicker" type="text" id="ge_periodoFim" name="ge_periodoFim" />
              </div>
            </div>
            <div class="form-actions">
              <input id="btnShowEscalaFunc" type="button" value="Exibir" class="btn btn-info" onClick="showEscalaFunc();" />
            </div>
          </form>
        </div>
      </div>

      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-briefcase"></i> </span>
          <h5>Resultado</h5>
        </div>
        <div class="widget-content nopadding">
          <div id="dvRetPostShowEscalaFunc">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
