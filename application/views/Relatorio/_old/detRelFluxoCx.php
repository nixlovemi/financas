<?php
$titulo    = isset($titulo) ? $titulo: "Detalhes";
$htmlTable = isset($htmlTable) ? $htmlTable: "";
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5><?php echo $titulo; ?></h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlTable;
    ?>
  </div>
</div>
