<?php
$basePath     = base_url();
$scriptPath   = isset($scriptPath) ? $scriptPath: "";
$arrInfoStart = isset($arrInfoStart) ? $arrInfoStart: array();
$iconSize     = "43px";
?>

<br />

<div class="widget-box widget-plain">
  <div class="center">
    <ul class="stat-boxes2">
      <?php
      if( isset($arrInfoStart["cts_receber"]) ){
        $totReceber = "$" . number_format($arrInfoStart["cts_receber"], 2, ",", ".");
        echo "<li>
                <div class='left'>
                  <a href='".$basePath."ContaReceber/index'>
                    <i style='font-size:$iconSize;' class='icon icon-money'></i>
                  </a>
                </div>
                <div class='right'>
                  <strong>$totReceber</strong>
                  A Receber no mês
                </div>
              </li>";
      }

      if( isset($arrInfoStart["cts_pagar"]) ){
        $totPagar = "$" . number_format($arrInfoStart["cts_pagar"], 2, ",", ".");
        echo "<li>
                <div class='left'>
                  <a href='".$basePath."ContaPagar/index'>
                    <i style='font-size:$iconSize;' class='icon icon-money'></i>
                  </a>
                </div>
                <div class='right'>
                  <strong>$totPagar</strong>
                  A Pagar no mês
                </div>
              </li>";
      }
      ?>
    </ul>
  </div>
</div>

<?php
/*
<div class="quick-actions_homepage">
  <ul class="quick-actions">
    <li class="bg_lb"> <a href="index.html"> <i class="icon-dashboard"></i> <span class="label label-important">20</span> My Dashboard </a> </li>
    <li class="bg_lg span3"> <a href="charts.html"> <i class="icon-signal"></i> Charts</a> </li>
    <li class="bg_ly"> <a href="widgets.html"> <i class="icon-inbox"></i><span class="label label-success">101</span> Widgets </a> </li>
    <li class="bg_lo"> <a href="tables.html"> <i class="icon-th"></i> Tables</a> </li>
    <li class="bg_ls"> <a href="grid.html"> <i class="icon-fullscreen"></i> Full width</a> </li>
    <li class="bg_lo span3"> <a href="form-common.html"> <i class="icon-th-list"></i> Forms</a> </li>
    <li class="bg_ls"> <a href="buttons.html"> <i class="icon-tint"></i> Buttons</a> </li>
    <li class="bg_lb"> <a href="interface.html"> <i class="icon-pencil"></i>Elements</a> </li>
    <li class="bg_lg"> <a href="calendar.html"> <i class="icon-calendar"></i> Calendar</a> </li>
    <li class="bg_lr"> <a href="error404.html"> <i class="icon-info-sign"></i> Error</a> </li>
  </ul>
</div>
*/
?>
