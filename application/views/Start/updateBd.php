<?php
$scriptPath  = isset($scriptPath) ? $scriptPath: "";
$arrFileInfo = pathinfo($scriptPath);

$fileName    = $arrFileInfo["filename"];
$strFileVers = str_replace("_", ".", str_replace("script_", "", $fileName));
?>

<h1>Atualização</h1>
<h3>Banco de Dados</h3>

<p>O sistema precisa atualizar o banco de dados para a versão mais recente.</p>
<p>Versão: <?php echo $strFileVers; ?></p>
<p>Para iniciar a atualização, clique em <b>Confirmar</b>.</p>
<p>Se quiser fazer essa atualização mais tarde, clique em fechar. Mas lembre-se que o sistema pode ficar instável sem essa atualização.</p>

<div id="dvLoaderBarUpdateDb"></div>
