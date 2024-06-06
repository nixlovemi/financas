<div class="widget-box">
  <div class="widget-title">
    <span class="icon"><i class="icon icon-print"></i></span>
    <h5>Detalhe Despesas do Mês</h5>
  </div>
  <div class="widget-content nopadding">
    <form id="frmRelDespesasMes" class="form-horizontal form-validation" method="post" action="<?=base_url()?>Relatorio/postRelDespesasMes">
      <div class="control-group">
        <label class="control-label">Período</label>
        <div class="controls">
          <input placeholder="Data Inicial" value="" class="mask_datepicker span3 m-wrap" type="text" id="frmDataIni" name="frmDataIni" />
          <input placeholder="Data Final" value="" class="mask_datepicker span3 m-wrap" type="text" id="frmDataFim" name="frmDataFim" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Download?</label>
        <div class="controls">
            <select id="frmDownload" name="frmDownload" style="width: 70%;">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-success">GERAR RELATÓRIO</button>
      </div>
    </form>
  </div>
</div>

<div id="dvPostRelDespesasMes"></div>