<?php
$htmlLancamentos  = isset($htmlLancamentos) ? $htmlLancamentos: "";
$htmlTotaisGastos = isset($htmlTotaisGastos) ? $htmlTotaisGastos: "";
$arrContas        = isset($arrContas) ? $arrContas: array();
$arrBaseDesp      = isset($arrBaseDesp) ? $arrBaseDesp: array();

$mesAtual = (int) date("m");
$anoAtual = (int) date("Y");
?>

<h1>Lançamentos</h1>

<a class="btn btn-info btn-large" href="javascript:;" id="btnJsonAddLancamento">NOVO LANÇAMENTO</a>
<a class="btn btn-info btn-large" href="javascript:;" id="">NOVA TRANSFERÊNCIA</a>

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
      <form id="frmFiltrosLancamentos" class="form-horizontal">
        <div class="control-group">
          <label class="control-label">Mês Base</label>
          <div class="controls">
            <select id="mes_base" name="mes_base">
              <?php
              $arrMes     = [];
              $arrMes[1]  = "Janeiro";
              $arrMes[2]  = "Fevereiro";
              $arrMes[3]  = "Março";
              $arrMes[4]  = "Abril";
              $arrMes[5]  = "Maio";
              $arrMes[6]  = "Junho";
              $arrMes[7]  = "Julho";
              $arrMes[8]  = "Agosto";
              $arrMes[9]  = "Setembro";
              $arrMes[10] = "Outubro";
              $arrMes[11] = "Novembro";
              $arrMes[12] = "Dezembro";

              foreach($arrMes as $nrMes => $strMes){
                $selected = ($mesAtual == $nrMes) ? " selected ": "";
                echo "<option $selected value='$nrMes'>$strMes</option>";
              }
              ?>
            </select>
            &nbsp;&nbsp;
            <select id="ano_base" name="ano_base">
              <?php
              $anoLimite = date("Y") + 2;
              for($i=2018;$i<=$anoLimite;$i++){
                $selected = ($anoAtual == $i) ? " selected ": "";
                echo "<option $selected value='$i'>$i</option>";
              }
              ?>
            </select>
          </div>
        </div>
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
          <label class="control-label">Contas</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterContas' id='filterContas'>
              <option value=''></option>
              <?php
              foreach($arrContas as $conta){
                $conId  = $conta["con_id"];
                $conDes = "[" . $conta["con_sigla"] . "] " . $conta["con_nome"];

                echo "<option value='$conId'>$conDes</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Tipo</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterTipo' id='filterTipo'>
              <option value=''></option>
              <option value='R'>Receita</option>
              <option value='D'>Despesa</option>
              <option value='T'>Transferência</option>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Categoria</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterCategoria' id='filterCategoria'>
              <option value=''></option>
              <?php
              foreach($arrBaseDesp as $categoria){
                $bdpId  = $categoria["bdp_id"];
                $bdpDes = $categoria["bdp_descricao"];

                echo "<option value='$bdpId'>$bdpDes</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Exibir</label>
          <div class="controls">
            <select class='span6 m-wrap' name='filterApenasPagas' id='filterApenasPagas'>
              <option value=''>Todos os Lançamentos</option>
              <option value='S'>Apenas Pagos</option>
              <option value='N'>Apenas não Pagos</option>
            </select>
          </div>
        </div>
        <div class="control-group" style="padding:8px 0;">
          <center>
            <a class="btn btn-info" href="javascript:;" id="btnFiltrarLancamentos">FILTRAR</a>
          </center>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-money"></i></span>
    <h5>Lançamentos</h5>
  </div>
  <div class="widget-content nopadding" id="dvHtmlLancamentos">
    <?php
    echo $htmlLancamentos;
    ?>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-money"></i></span>
    <h5>Totais Gastos</h5>
  </div>
  <div class="widget-content" id="dvHtmlTotaisGastos">
    <?php
    echo $htmlTotaisGastos;
    ?>
  </div>
</div>
