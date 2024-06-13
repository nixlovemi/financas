<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Nova Transferência";
$arrConta   = isset($arrConta) ? $arrConta: array();
?>

<div class="container-fluid">
  <h1><?php echo $h1Title; ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Lançamento</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmJsonAddTransferencia" class="form-horizontal form-validation" method="post" action="">
            <div class="control-group">
              <label class="control-label">Vencimento</label>
              <div class="controls">
                <input value="" class="span10 mask_datepicker" type="text" id="tVencimento" name="tVencimento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><?=CURRENCY_SYMBOL?></span>
                  <input class="span10 mask_moeda" type="text" name="tValor" id="tValor" value="" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">De:</label>
              <div class="controls">
                <?php
                echo "<select class='span10' name='tContaDe' id='tContaDe'>";
                echo "<option value=''></option>";
                foreach($arrConta as $conta){
                  $conId    = $conta["con_id"];
                  $conDesc  = "[" . $conta["con_sigla"] . "] " . $conta["con_nome"];

                  echo "<option value='$conId'>$conDesc</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Para:</label>
              <div class="controls">
                <?php
                echo "<select class='span10' name='tContaPara' id='tContaPara'>";
                echo "<option value=''></option>";
                foreach($arrConta as $conta){
                  $conId    = $conta["con_id"];
                  $conDesc  = "[" . $conta["con_sigla"] . "] " . $conta["con_nome"];

                  echo "<option value='$conId'>$conDesc</option>";
                }
                echo "</select>";
                ?>
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
