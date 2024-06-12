<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Novo Lançamento";

$arrBaseDesp = isset($arrBaseDesp) ? $arrBaseDesp: array();
$arrConta    = isset($arrConta) ? $arrConta: array();
$Lancamento  = isset($Lancamento) ? $Lancamento : array();
$detalhes    = isset($detalhes) ? $detalhes: false;
$editar      = isset($editar) ? $editar: false;

$strReadyonly = ($detalhes) ? " readonly ": "";

$vLanId         = isset($Lancamento["lan_id"]) ? $Lancamento["lan_id"]: "";
$vLanDespesa    = isset($Lancamento["lan_despesa"]) ? $Lancamento["lan_despesa"]: "";
$vLanTipo       = isset($Lancamento["lan_tipo"]) ? $Lancamento["lan_tipo"]: "";
$vLanCompra     = isset($Lancamento["lan_compra"]) && strlen($Lancamento["lan_compra"]) == 10 ? date("d/m/Y", strtotime($Lancamento["lan_compra"])): "";
$vLanVencimento = isset($Lancamento["lan_vencimento"]) && strlen($Lancamento["lan_vencimento"]) == 10 ? date("d/m/Y", strtotime($Lancamento["lan_vencimento"])): "";
$vLanValor      = isset($Lancamento["lan_valor"]) ? number_format($Lancamento["lan_valor"], 2, ",", ""): "";
$vLanPagamento  = isset($Lancamento["lan_pagamento"]) && strlen($Lancamento["lan_pagamento"]) == 10 ? date("d/m/Y", strtotime($Lancamento["lan_pagamento"])): "";
$vLanValorPago  = isset($Lancamento["lan_valor_pago"]) ? number_format($Lancamento["lan_valor_pago"], 2, ",", ""): "";
$vLanConta      = isset($Lancamento["lan_conta"]) ? $Lancamento["lan_conta"]: "";
$vLanObservacao = isset($Lancamento["lan_observacao"]) ? $Lancamento["lan_observacao"]: "";
$vLanConfirmado = isset($Lancamento["lan_confirmado"]) ? $Lancamento["lan_confirmado"]: 0;

$arrCategorias = isset($Lancamento["ld_bdp_id"]) ? $Lancamento["ld_bdp_id"]: array();
$arrCategoriasVlr = isset($Lancamento["ld_valor"]) ? $Lancamento["ld_valor"]: array();

$vCheckConfirm  = $vLanConfirmado == 1 ? " checked ": "";

if($editar){
  $h1Title = "Editar Lançamento";
}
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
          <form id="frmJsonAddLancamento" class="form-horizontal form-validation" method="post" action="">
            <?php
            if($detalhes || $editar){
              ?>
              <div class="control-group">
                <label class="control-label">ID</label>
                <div class="controls">
                  <input readonly class="span10" type="text" name="lanId" id="lanId" value="<?php echo $vLanId; ?>" />
                </div>
              </div>
              <?php
            }
            ?>
            <div class="control-group">
              <label class="control-label">Descrição</label>
              <div class="controls">
                <input <?php echo $strReadyonly; ?> value="<?php echo $vLanDespesa; ?>" class="span10" type="text" id="lanDespesa" name="lanDespesa" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Tipo</label>
              <div class="controls">
                <?php
                $arrTiposId  = array("D", "R");
                $arrTiposTxt = array("Despesa", "Receita");

                echo "<select $strReadyonly class='span10' name='lanTipo' id='lanTipo'>";
                for($i=0; $i<count($arrTiposId);$i++){
                  $tipoId   = $arrTiposId[$i];
                  $tipoTxt  = $arrTiposTxt[$i];
                  $selected = ($tipoId == $vLanTipo) ? " selected ": "";

                  echo "<option $selected value='$tipoId'>$tipoTxt</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label">Dt Compra (Opcional)</label>
              <div class="controls">
                <input value="<?php echo $vLanCompra; ?>" class="span10 mask_datepicker" type="text" id="lanCompra" name="lanCompra" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Vencimento</label>
              <div class="controls">
                <input value="<?php echo $vLanVencimento; ?>" class="span10 mask_datepicker" type="text" id="lanVencimento" name="lanVencimento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><?=CURRENCY_SYMBOL?></span>
                  <input class="span10 mask_moeda" type="text" name="lanValor" id="lanValor" value="<?php echo $vLanValor; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Pagamento</label>
              <div class="controls">
                <input value="<?php echo $vLanPagamento; ?>" class="span10 mask_datepicker" type="text" id="lanPagamento" name="lanPagamento" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor Pago</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><?=CURRENCY_SYMBOL?></span>
                  <input class="span10 mask_moeda" type="text" name="lanValorPago" id="lanValorPago" value="<?php echo $vLanValorPago; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Categoria</label>
              <div class="controls">
                <a id="btn-nova-linha" style="margin-bottom:8px;" class="btn btn-small btn-success" href="javascript:;">Nova linha</a>

                <table id="tblLancamentoDespesa" class="table table-bordered table-condensed table-striped">
                  <thead>
                    <tr>
                      <th>Categoria</th>
                      <th>Valor</th>
                      <th>Remover</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                      <?php
                      $i = 0;
                      do {
                      ?>
                        <tr>
                          <td width="50%">
                            <?php
                            $trashDisplay = $i == 0 ? "display:none;": "";
                            $categoria1 = isset($arrCategorias[$i]) ? $arrCategorias[$i]: "";
                            $categoriaVlr1 = isset($arrCategoriasVlr[$i]) ? number_format($arrCategoriasVlr[$i], 2, ",", ""): "";

                            echo "<select $strReadyonly class='span10' name='ldBdpId[]'>";
                            echo "<option value=''></option>";
                            foreach($arrBaseDesp as $categoria){
                              $bdpId    = $categoria["bdp_id"];
                              $bdpDesc  = $categoria["bdp_descricao"];
                              $selected = ($bdpId == $categoria1) ? " selected ": "";

                              echo "<option $selected value='$bdpId'>$bdpDesc</option>";
                            }
                            echo "</select>";
                            ?>
                          </td>
                          <td width="40%">
                            <div class="input-prepend">
                              <span class="add-on"><?=CURRENCY_SYMBOL?></span>
                              <input <?=$strReadyonly?> class="span8 mask_moeda" type="text" name="ldValor[]" value="<?=$categoriaVlr1?>" />
                            </div>
                          </td>
                          <td width="10%" style="text-align:center">
                            <?php if($strReadyonly == ''): ?>
                              <a style="<?=$trashDisplay?>" href="javascript:;" class="TbLancamentoDespesa_ajax_deletar">
                                <i class="icon-trash icon-lista"></i>
                              </a>
                            <?php endif; ?>
                          </td>
                        </tr>

                        <?php
                          $i++;
                        } while($i < count($arrCategorias));
                      ?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Conta</label>
              <div class="controls">
                <?php
                echo "<select $strReadyonly class='span10' name='lanConta' id='lanConta'>";
                echo "<option value=''></option>";
                foreach($arrConta as $conta){
                  $conId    = $conta["con_id"];
                  $conDesc  = "[" . $conta["con_sigla"] . "] " . $conta["con_nome"];
                  $selected = ($conId == $vLanConta) ? " selected ": "";

                  echo "<option $selected value='$conId'>$conDesc</option>";
                }
                echo "</select>";
                ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Observação</label>
              <div class="controls">
                <textarea <?php echo $strReadyonly; ?> class="span10" name="lanObservacao" id="lanObservacao"><?php echo $vLanObservacao; ?></textarea>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Confirmado</label>
              <div class="controls">
                <input <?php echo $vCheckConfirm; ?> type="checkbox" name="lanConfirmado" id="lanConfirmado" value="1" />
              </div>
            </div>
            <?php
            if(!$detalhes && !$editar){
              ?>
              <div class="control-group">
                <label class="control-label">Repetir nos próximos</label>
                <div class="controls">
                  <div class="input-append">
                    <input class="span10 mask_inteiro" type="text" name="repeteMeses" id="repeteMeses" value="<?php echo $vLanId; ?>" />
                    <span class="add-on">meses</span>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
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