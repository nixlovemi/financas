<?php
$this->load->helper('utils');
$this->load->helper('alerts');

// variaveis =====
$arrFuncionarioDados   = isset($arrFuncionarioDados) ? $arrFuncionarioDados: array();
$errorMsg          = isset($errorMsg) ? $errorMsg: "";
$okMsg             = isset($okMsg) ? $okMsg: "";
$detalhes          = isset($detalhes) ? $detalhes: false;
$editar            = isset($editar) ? $editar: false;
$queries           = isset($queries) && $queries != "" ? "?" . $queries: ""; // esquema do dynatables
$arrEstados        = isset($arrEstados) ? $arrEstados: array();

$vFunId            = isset($arrFuncionarioDados["fun_id"]) ? $arrFuncionarioDados["fun_id"]: "";
$vFunNome          = isset($arrFuncionarioDados["fun_nome"]) ? $arrFuncionarioDados["fun_nome"]: "";
$vFunCpfCnpj       = isset($arrFuncionarioDados["fun_cpf_cnpj"]) ? $arrFuncionarioDados["fun_cpf_cnpj"]: "";
$vFunRgIe          = isset($arrFuncionarioDados["fun_rg_ie"]) ? $arrFuncionarioDados["fun_rg_ie"]: "";
$vFunTelDdd        = isset($arrFuncionarioDados["fun_tel_ddd"]) ? $arrFuncionarioDados["fun_tel_ddd"]: "";
$vFunTelNumero     = isset($arrFuncionarioDados["fun_tel_numero"]) ? $arrFuncionarioDados["fun_tel_numero"]: "";
$vFunCelDdd        = isset($arrFuncionarioDados["fun_cel_ddd"]) ? $arrFuncionarioDados["fun_cel_ddd"]: "";
$vFunCelNumero     = isset($arrFuncionarioDados["fun_cel_numero"]) ? $arrFuncionarioDados["fun_cel_numero"]: "";
$vFunEndCep        = isset($arrFuncionarioDados["fun_end_cep"]) ? $arrFuncionarioDados["fun_end_cep"]: "";
$vFunEndTpLgr      = isset($arrFuncionarioDados["fun_end_tp_lgr"]) ? $arrFuncionarioDados["fun_end_tp_lgr"]: "";
$vFunEndLogradouro = isset($arrFuncionarioDados["fun_end_logradouro"]) ? $arrFuncionarioDados["fun_end_logradouro"]: "";
$vFunEndNumero     = isset($arrFuncionarioDados["fun_end_numero"]) ? $arrFuncionarioDados["fun_end_numero"]: "";
$vFunEndBairro     = isset($arrFuncionarioDados["fun_end_bairro"]) ? $arrFuncionarioDados["fun_end_bairro"]: "";
$vFunEndCidade     = isset($arrFuncionarioDados["fun_end_cidade"]) ? $arrFuncionarioDados["fun_end_cidade"]: "";
$vFunEndEstado     = isset($arrFuncionarioDados["fun_end_estado"]) ? $arrFuncionarioDados["fun_end_estado"]: "";
$vFunObs           = isset($arrFuncionarioDados["fun_observacao"]) ? $arrFuncionarioDados["fun_observacao"]: "";
$vFunAtivo         = isset($arrFuncionarioDados["fun_ativo"]) ? $arrFuncionarioDados["fun_ativo"]: "";
// ===============

// info do form ==
if($vFunAtivo === false || $vFunAtivo == "false" || $vFunAtivo == "f"){
  $selecAtivoS = "";
  $selecAtivoN = " selected ";
} else {
  $selecAtivoS = " selected ";
  $selecAtivoN = "";
}

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$arrButtons = [];
$h1Title    = "Novo Funcionario";

if($detalhes){
  $h1Title      = "Detalhes do Funcionario";
  $arrButtons[] = "<a href='".base_url() . "Funcionario/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "";
} elseif($editar){
  $h1Title      = "Editar Funcionario";
  $arrButtons[] = "<input type='submit' value='Gravar Edição' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Funcionario/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "Funcionario/salvaEditar";
} else {
  $h1Title      = "Novo Funcionario";
  $arrButtons[] = "<input type='submit' value='Gravar Funcionario' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Funcionario/index$queries" ."' class='btn'>Cancelar</a>";
  $frmAction    = "Funcionario/salvaNovo";
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
        <h5>Funcionario</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . $frmAction; ?>">
          <?php
          if($detalhes || $editar){
            ?>
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="funId" id="funId" value="<?php echo $vFunId; ?>" />
              </div>
            </div>
            <?php
          }
          ?>

          <div class="control-group">
            <label class="control-label">Nome</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="funNome" id="funNome" value="<?php echo $vFunNome; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">CPF</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10 mask_cpf" type="text" name="funCpfCnpj" id="funCpfCnpj" value="<?php echo $vFunCpfCnpj; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">RG</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="funRgIe" id="funRgIe" value="<?php echo $vFunRgIe; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Telefone</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="funTelDdd" id="funTelDdd" value="<?php echo $vFunTelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Telefone" class="span8 m-wrap" type="text" name="funTelNumero" id="funTelNumero" value="<?php echo $vFunTelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Celular</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="funCelDdd" id="funCelDdd" value="<?php echo $vFunCelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Celular" class="span8 m-wrap" type="text" name="funCelNumero" id="funCelNumero" value="<?php echo $vFunCelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <div style="height:20px;"></div>
          </div>

          <div class="control-group">
            <label class="control-label">CEP</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10 mask_cep" type="text" name="funEndCep" id="funEndCep" value="<?php echo $vFunEndCep; ?>" />
            </div>
          </div>

          <?php
          $arrTpLogr = array("", "Alameda", "Avenida", "Condomínio", "Distrito", "Estrada", "Praça", "Residencial", "Rodovia", "Rua");
          ?>
          <div class="control-group">
            <label class="control-label">Endereço</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <select <?php echo $strDisabled; ?> class="span2 m-wrap" name="funEndTpLgr" id="funEndTpLgr">
                <?php
                foreach($arrTpLogr as $tpLogr){
                  $selected = ($tpLogr == $vFunEndTpLgr ) ? " selected ": "";
                  echo "<option $selected value='$tpLogr'>$tpLogr</option>";
                }
                ?>
              </select>
              <input <?php echo $strReadyonly; ?> class="span6 m-wrap" type="text" name="funEndLogradouro" id="funEndLogradouro" value="<?php echo $vFunEndLogradouro; ?>" />
              <input <?php echo $strReadyonly; ?> class="span2 m-wrap" type="text" name="funEndNumero" id="funEndNumero" value="<?php echo $vFunEndNumero; ?>" placeholder="Número" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Bairro</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="funEndBairro" id="funEndBairro" value="<?php echo $vFunEndBairro; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Cidade</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span7 m-wrap" type="text" name="funEndCidade" id="funEndCidade" value="<?php echo $vFunEndCidade; ?>" />

              <?php
              echo "<select $strDisabled class='span3 m-wrap' name='funEndEstado' id='funEndEstado'>";
              echo "<option value=''></option>";
              foreach($arrEstados as $Estado){
                $estSigla = $Estado["est_sigla"];
                $estDesc  = $Estado["est_descricao"];

                $selected = ($estSigla == $vFunEndEstado ) ? " selected ": "";
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
              <textarea <?php echo $strReadyonly; ?> class="span10" name="funObservacao" id="funObservacao"><?php echo $vFunObs; ?></textarea>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Ativo</label>
            <div class="controls">
              <select <?php echo $strDisabled; ?> class="" name="funAtivo" id="funAtivo">
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
