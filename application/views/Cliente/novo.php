<?php
$this->load->helper('utils');
$this->load->helper('alerts');

// variaveis =====
$arrClienteDados   = isset($arrClienteDados) ? $arrClienteDados: array();
$errorMsg          = isset($errorMsg) ? $errorMsg: "";
$okMsg             = isset($okMsg) ? $okMsg: "";
$detalhes          = isset($detalhes) ? $detalhes: false;
$editar            = isset($editar) ? $editar: false;
$queries           = isset($queries) && $queries != "" ? "?" . $queries: ""; // esquema do dynatables
$arrEstados        = isset($arrEstados) ? $arrEstados: array();

$vCliId            = isset($arrClienteDados["cli_id"]) ? $arrClienteDados["cli_id"]: "";
$vCliNome          = isset($arrClienteDados["cli_nome"]) ? $arrClienteDados["cli_nome"]: "";
$vCliCpfCnpj       = isset($arrClienteDados["cli_cpf_cnpj"]) ? $arrClienteDados["cli_cpf_cnpj"]: "";
$vCliRgIe          = isset($arrClienteDados["cli_rg_ie"]) ? $arrClienteDados["cli_rg_ie"]: "";
$vCliTelDdd        = isset($arrClienteDados["cli_tel_ddd"]) ? $arrClienteDados["cli_tel_ddd"]: "";
$vCliTelNumero     = isset($arrClienteDados["cli_tel_numero"]) ? $arrClienteDados["cli_tel_numero"]: "";
$vCliCelDdd        = isset($arrClienteDados["cli_cel_ddd"]) ? $arrClienteDados["cli_cel_ddd"]: "";
$vCliCelNumero     = isset($arrClienteDados["cli_cel_numero"]) ? $arrClienteDados["cli_cel_numero"]: "";
$vCliEndCep        = isset($arrClienteDados["cli_end_cep"]) ? $arrClienteDados["cli_end_cep"]: "";
$vCliEndTpLgr      = isset($arrClienteDados["cli_end_tp_lgr"]) ? $arrClienteDados["cli_end_tp_lgr"]: "";
$vCliEndLogradouro = isset($arrClienteDados["cli_end_logradouro"]) ? $arrClienteDados["cli_end_logradouro"]: "";
$vCliEndNumero     = isset($arrClienteDados["cli_end_numero"]) ? $arrClienteDados["cli_end_numero"]: "";
$vCliEndBairro     = isset($arrClienteDados["cli_end_bairro"]) ? $arrClienteDados["cli_end_bairro"]: "";
$vCliEndCidade     = isset($arrClienteDados["cli_end_cidade"]) ? $arrClienteDados["cli_end_cidade"]: "";
$vCliEndEstado     = isset($arrClienteDados["cli_end_estado"]) ? $arrClienteDados["cli_end_estado"]: "";
$vCliObs           = isset($arrClienteDados["cli_observacao"]) ? $arrClienteDados["cli_observacao"]: "";
$vCliAtivo         = isset($arrClienteDados["cli_ativo"]) ? $arrClienteDados["cli_ativo"]: "";
// ===============

// info do form ==
if($vCliAtivo === false || $vCliAtivo == "false" || $vCliAtivo == "f"){
  $selecAtivoS = "";
  $selecAtivoN = " selected ";
} else {
  $selecAtivoS = " selected ";
  $selecAtivoN = "";
}

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$arrButtons = [];
$h1Title    = "Novo Cliente";

if($detalhes){
  $h1Title      = "Detalhes do Cliente";
  $arrButtons[] = "<a href='".base_url() . "Cliente/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "";
} elseif($editar){
  $h1Title      = "Editar Cliente";
  $arrButtons[] = "<input type='submit' value='Gravar Edição' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Cliente/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "Cliente/salvaEditar";
} else {
  $h1Title      = "Novo Cliente";
  $arrButtons[] = "<input type='submit' value='Gravar Cliente' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Cliente/index$queries" ."' class='btn'>Cancelar</a>";
  $frmAction    = "Cliente/salvaNovo";
}
// ===============
?>

<h1><?php echo $h1Title; ?></h1>

<?php
if($errorMsg != ""){
  echo showWarning($errorMsg);
} else if($okMsg != ""){
  echo showSuccess($okMsg);
}
?>

<div class="row-fluid">
  <div class="span12">
    <div class="widget-box">
      <div class="widget-title">
        <span class="icon"> <i class="icon icon-tasks"></i> </span>
        <h5>Cliente</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . $frmAction; ?>">
          <?php
          if($detalhes || $editar){
            ?>
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="cliId" id="cliId" value="<?php echo $vCliId; ?>" />
              </div>
            </div>
            <?php
          }
          ?>

          <div class="control-group">
            <label class="control-label">Nome</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="cliNome" id="cliNome" value="<?php echo $vCliNome; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">CPF/CNPJ</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="cliCpfCnpj" id="cliCpfCnpj" value="<?php echo $vCliCpfCnpj; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">RG/IE</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="cliRgIe" id="cliRgIe" value="<?php echo $vCliRgIe; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Telefone</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="cliTelDdd" id="cliTelDdd" value="<?php echo $vCliTelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Telefone" class="span8 m-wrap" type="text" name="cliTelNumero" id="cliTelNumero" value="<?php echo $vCliTelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Celular</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="cliCelDdd" id="cliCelDdd" value="<?php echo $vCliCelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Celular" class="span8 m-wrap" type="text" name="cliCelNumero" id="cliCelNumero" value="<?php echo $vCliCelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <div style="height:20px;"></div>
          </div>

          <div class="control-group">
            <label class="control-label">CEP</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10 mask_cep" type="text" name="cliEndCep" id="cliEndCep" value="<?php echo $vCliEndCep; ?>" />
            </div>
          </div>

          <?php
          $arrTpLogr = array("", "Alameda", "Avenida", "Condomínio", "Distrito", "Estrada", "Praça", "Residencial", "Rodovia", "Rua");
          ?>
          <div class="control-group">
            <label class="control-label">Endereço</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <select <?php echo $strDisabled; ?> class="span2 m-wrap" name="cliEndTpLgr" id="cliEndTpLgr">
                <?php
                foreach($arrTpLogr as $tpLogr){
                  $selected = ($tpLogr == $vCliEndTpLgr ) ? " selected ": "";
                  echo "<option $selected value='$tpLogr'>$tpLogr</option>";
                }
                ?>
              </select>
              <input <?php echo $strReadyonly; ?> class="span6 m-wrap" type="text" name="cliEndLogradouro" id="cliEndLogradouro" value="<?php echo $vCliEndLogradouro; ?>" />
              <input <?php echo $strReadyonly; ?> class="span2 m-wrap" type="text" name="cliEndNumero" id="cliEndNumero" value="<?php echo $vCliEndNumero; ?>" placeholder="Número" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Bairro</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="cliEndBairro" id="cliEndBairro" value="<?php echo $vCliEndBairro; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Cidade</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span7 m-wrap" type="text" name="cliEndCidade" id="cliEndCidade" value="<?php echo $vCliEndCidade; ?>" />

              <?php
              echo "<select $strDisabled class='span3 m-wrap' name='cliEndEstado' id='cliEndEstado'>";
              echo "<option value=''></option>";
              foreach($arrEstados as $Estado){
                $estSigla = $Estado["est_sigla"];
                $estDesc  = $Estado["est_descricao"];

                $selected = ($estSigla == $vCliEndEstado ) ? " selected ": "";
                echo "<option $selected value='$estSigla'>$estDesc</option>";
              }
              echo "</select>";
              ?>
            </div>
          </div>

          <div class="control-group">
            <div style="height:20px;"></div>
          </div>

          <div class="control-group">
            <label class="control-label">Observação</label>
            <div class="controls">
              <!-- validate-required -->
              <textarea <?php echo $strReadyonly; ?> class="span10" name="cliObservacao" id="cliObservacao"><?php echo $vCliObs; ?></textarea>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Ativo</label>
            <div class="controls">
              <select <?php echo $strDisabled; ?> class="" name="cliAtivo" id="cliAtivo">
                <option value="1" <?php echo $selecAtivoS; ?>>Sim</option>
                <option value="0" <?php echo $selecAtivoN; ?>>Não</option>
              </select>
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
