<?php
// variaveis ============
$arrRelFluxo = isset($arrRelFluxo) ? $arrRelFluxo: array();
// ======================

include APPPATH . 'third_party/customFPDF/customFPDF.php';
$userData = $this->session->get_userdata();
$username = $userData["logged_in"]["usu_nome"];

$cFPDF = new customFPDF("RELATÓRIO FLUXO DE CAIXA", "Relatorio/pdfRelFluxoCx", "L", $username); //289
$cFPDF->Open();
$cFPDF->AliasNbPages();
$cFPDF->AddPage();

$cFPDF->SetFont('arial', 'B', 10);
$cFPDF->SetFillColor(230, 225, 225);

$cFPDF->Cell(29, 5, 'Dia', 1, 0, 'C', true);
$cFPDF->Cell(52, 5, 'Contas a Receber', 1, 0, 'C', true);
$cFPDF->Cell(52, 5, 'Contas a Pagar', 1, 0, 'C', true);
$cFPDF->Cell(52, 5, 'Saldo Inicial', 1, 0, 'C', true);
$cFPDF->Cell(52, 5, 'Saldo do Dia', 1, 0, 'C', true);
$cFPDF->Cell(52, 5, 'Acumulado', 1, 0, 'C', true);
$cFPDF->Ln(5);

$cFPDF->SetFont('arial', '', 9);
$cFPDF->SetFillColor(255, 255, 255);

$i = 1;
foreach($arrRelFluxo as $row){
  $dia       = utf8_decode($row["dia"]);
  $receber   = "$" . number_format($row["receber"], 2, ",", ".");
  $pagar     = "$" . number_format($row["pagar"], 2, ",", ".");
  $saldoIni  = ($row["saldo_inicial"] > 0) ? "$" . number_format($row["saldo_inicial"], 2, ",", "."): "";
  $saldoDia  = "$" . number_format($row["saldo_dia"], 2, ",", ".");
  $acumulado = "$" . number_format($row["acumulado"], 2, ",", ".");

  if ($i % 2 == 0) {
    $cFPDF->SetFillColor(252, 252, 252);
  } else {
    $cFPDF->SetFillColor(235, 235, 235);
  }

  // celulas
  $cFPDF->SetTextColor(0, 0, 0);
  $cFPDF->Cell(29, 5, $dia, 1, 0, 'C', true);

  $cFPDF->SetTextColor(0, 0, 255);
  $cFPDF->Cell(52, 5, $receber, 1, 0, 'C', true);

  $cFPDF->SetTextColor(255, 0, 0);
  $cFPDF->Cell(52, 5, $pagar, 1, 0, 'C', true);

  $cFPDF->SetTextColor(0, 0, 0);
  $cFPDF->Cell(52, 5, $saldoIni, 1, 0, 'C', true);

  if($saldoDia < 0){
    $cFPDF->SetTextColor(255, 0, 0);
  } else {
    $cFPDF->SetTextColor(0, 0, 255);
  }
  $cFPDF->Cell(52, 5, $saldoDia, 1, 0, 'C', true);

  if($acumulado < 0){
    $cFPDF->SetTextColor(255, 0, 0);
  } else {
    $cFPDF->SetTextColor(0, 0, 255);
  }
  $cFPDF->Cell(52, 5, $acumulado, 1, 0, 'C', true);
  // =======

  $i++;
  $cFPDF->Ln(5);
}

$cFPDF->SetTextColor(0, 0, 0);
$cFPDF->Output();
