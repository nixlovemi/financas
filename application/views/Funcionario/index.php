<h1>Funcionários</h1>

<a class="btn btn-info btn-large" href="<?php echo base_url() . "Funcionario/novoFuncionario"; ?>">NOVO FUNCIONÁRIO</a>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-tasks"></i></span>
    <h5>Lista de funcionários</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlFunTable;
    ?>
  </div>
</div>
