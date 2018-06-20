<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Nova Escala";

$FuncionarioEscala = isset($FuncionarioEscala) ? $FuncionarioEscala : array();
$editar            = isset($editar) ? $editar: false;
$arrClientes       = (isset($arrClientes)) ? $arrClientes: array();
$arrFuncionario    = (isset($arrFuncionario)) ? $arrFuncionario: array();

$vFueId = isset($FuncionarioEscala["fue_id"]) ? $FuncionarioEscala["fue_id"]: "";
$vFunId = isset($FuncionarioEscala["fue_fun_id"]) ? $FuncionarioEscala["fue_fun_id"]: "";
$vCliId = isset($FuncionarioEscala["fue_cli_id"]) ? $FuncionarioEscala["fue_cli_id"]: "";
$vDtIni = isset($FuncionarioEscala["fue_dtinicio"]) && $FuncionarioEscala["fue_dtinicio"] != "" ? date("d/m/Y", strtotime($FuncionarioEscala["fue_dtinicio"])): "";
$vHrIni = isset($FuncionarioEscala["fue_dtinicio"]) && $FuncionarioEscala["fue_dtinicio"] != "" ? date("H:i", strtotime($FuncionarioEscala["fue_dtinicio"])): "";
$vDtFim = isset($FuncionarioEscala["fue_dtfim"]) && $FuncionarioEscala["fue_dtfim"] != "" ? date("d/m/Y", strtotime($FuncionarioEscala["fue_dtfim"])): "";
$vHrFim = isset($FuncionarioEscala["fue_dtfim"]) && $FuncionarioEscala["fue_dtfim"] != "" ? date("H:i", strtotime($FuncionarioEscala["fue_dtfim"])): "";
$vObs   = isset($FuncionarioEscala["fue_obs"]) ? $FuncionarioEscala["fue_obs"]: "";

$vFunId = (isset($funId)) ? $funId: $vFunId;

if($editar){
  $h1Title = "Editar Escala";
}
?>

<div class="container-fluid">
  <h1><?php echo $h1Title; ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Escala</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmJsonAddFuncEscala" class="form-horizontal form-validation" method="post" action="">
            <?php
            if($editar){
              ?>
              <div class="control-group">
                <label class="control-label">ID</label>
                <div class="controls">
                  <input readonly class="span10" type="text" name="fueId" id="fueId" value="<?php echo $vFueId; ?>" />
                </div>
              </div>
              <?php
            }
            ?>
            <div class="control-group">
              <label class="control-label">Funcionário</label>
              <div class="controls">
                <!-- validate-required -->
                <?php
                echo "<select class='span10 m-wrap' name='fueFunId' id='fueFunId'>";
                echo "<option value=''></option>";
                foreach($arrFuncionario as $Funcionario){
                  echo "$vFunId|$funId";
                  $funId    = $Funcionario["fun_id"];
                  $funNome  = $Funcionario["fun_nome"];
                  $selected = ($vFunId == $funId) ? " selected ": "";

                  echo "<option $selected value='$funId'>$funNome</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Cliente</label>
              <div class="controls">
                <!-- validate-required -->
                <?php
                echo "<select class='span10 m-wrap' name='fueCliId' id='fueCliId'>";
                echo "<option value=''></option>";
                foreach($arrClientes as $Cliente){
                  $cliId    = $Cliente["cli_id"];
                  $cliNome  = $Cliente["cli_nome"];
                  $selected = ($vCliId == $cliId) ? " selected ": "";

                  echo "<option $selected value='$cliId'>$cliNome</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Início</label>
              <div class="controls">
                <input placeholder="Data" value="<?php echo $vDtIni; ?>" class="span4 mask_datepicker" type="text" id="fueDtIni" name="fueDtIni" />
                <input placeholder="Hora" value="<?php echo $vHrIni; ?>" class="span4 mask_hora" type="text" id="fueDtIniHora" name="fueDtIniHora" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Fim</label>
              <div class="controls">
                <input placeholder="Data" value="<?php echo $vDtFim; ?>" class="span4 mask_datepicker" type="text" id="fueDtFim" name="fueDtFim" />
                <input placeholder="Hora" value="<?php echo $vHrFim; ?>" class="span4 mask_hora" type="text" id="fueDtFimHora" name="fueDtFimHora" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Observação</label>
              <div class="controls">
                <textarea class="span10" name="fueObs" id="fueObs"><?php echo $vObs; ?></textarea>
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
