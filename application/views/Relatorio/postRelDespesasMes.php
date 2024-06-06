<?php
// CONTROLLER VARS
$message = isset($message) ? $message: null;
$queryArr = isset($queryArr) && is_array($queryArr) ? $queryArr: [];
$downloadXls = isset($downloadXls) ? (bool) $downloadXls: false;

if ($message !== null) {
    echo $message;
    return;
}

if ($downloadXls) {
    $dateStr = date('YmdHis');
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment;filename="relatorio_'.$dateStr.'.xls"');
    header('Cache-Control: max-age=0');
}

// TABLE
$totalGeral = 0;
$totalPagoGeral = 0;

$tbBorder = ($downloadXls) ? ' border="1" ': '';
$htmlTable = '<table '.$tbBorder.' class="table table-bordered">';
foreach ($queryArr as $categoryStr => $row) {
    $total = 0;
    $totalPago = 0;

    $htmlTable .= '<thead>';
    $htmlTable .= '  <tr>';
    $htmlTable .= '    <th colspan="10"><b><center>'.$categoryStr.'</center></b></th>';
    $htmlTable .= '  </tr>';

    $htmlTable .= '  <tr>';
    $htmlTable .= '    <th>ID</th>';
    $htmlTable .= '    <th>Despesa</th>';
    $htmlTable .= '    <th>Parcela</th>';
    $htmlTable .= '    <th>Dt Compra</th>';
    $htmlTable .= '    <th>Dt Vcto</th>';
    $htmlTable .= '    <th>Valor</th>';
    $htmlTable .= '    <th>Dt Pgto</th>';
    $htmlTable .= '    <th>Valor Pago</th>';
    $htmlTable .= '    <th>Conta</th>';
    $htmlTable .= '    <th>Observação</th>';
    $htmlTable .= '  </tr>';
    $htmlTable .= '</thead>';

    $htmlTable .= '<tbody>';
    foreach ($row as $item) {
        // VARIAVEIS
        $id = isset($item['lan_id']) ? $item['lan_id']: '';
        $despesa = isset($item['lan_despesa']) ? $item['lan_despesa']: '';
        $parcela = isset($item['lan_parcela']) ? $item['lan_parcela']: '';
        $dtCompra = isset($item['lan_compra']) ? $item['lan_compra']: '';
        $fDtCompra = (strlen($dtCompra) === 10) ? date('d/m/Y', strtotime($dtCompra)) : '';
        $dtVcto = isset($item['lan_vencimento']) ? $item['lan_vencimento']: '';
        $fDtVcto = (strlen($dtVcto) === 10) ? date('d/m/Y', strtotime($dtVcto)) : '';
        $valor = isset($item['lan_valor']) ? $item['lan_valor']: '';
        $fValor = ($valor <> '' && !$downloadXls) ? CURRENCY_SYMBOL . number_format($valor, 2, ',', '.'): $valor;
        $dtPgto = isset($item['lan_pagamento']) ? $item['lan_pagamento']: '';
        $fDtPgto = (strlen($dtPgto) === 10) ? date('d/m/Y', strtotime($dtPgto)) : '';
        $valorPago = isset($item['lan_valor_pago']) ? $item['lan_valor_pago']: '';
        $fValorPago = ($valorPago <> '' && !$downloadXls) ? CURRENCY_SYMBOL . number_format($valorPago, 2, ',', '.'): $valorPago;
        $conta = isset($item['con_nome']) ? $item['con_nome']: '';
        $obs = isset($item['lan_observacao']) ? $item['lan_observacao']: '';

        // TOTAIS
        if ($valor !== '') {
            $total += $valor;
        }
        if ($valorPago !== '') {
            $totalPago += $valorPago;
        }

        $htmlTable .= '<tr>';
        $htmlTable .= '  <td>'.$id.'</td>';
        $htmlTable .= '  <td>'.$despesa.'</td>';
        $htmlTable .= '  <td>'.$parcela.'</td>';
        $htmlTable .= '  <td>'.$fDtCompra.'</td>';
        $htmlTable .= '  <td>'.$fDtVcto.'</td>';
        $htmlTable .= '  <td>'.$fValor.'</td>';
        $htmlTable .= '  <td>'.$fDtPgto.'</td>';
        $htmlTable .= '  <td>'.$fValorPago.'</td>';
        $htmlTable .= '  <td>'.$conta.'</td>';
        $htmlTable .= '  <td>'.$obs.'</td>';
        $htmlTable .= '</tr>';
    }

    // TOTAL ROW
    $fTotal = (!$downloadXls) ? CURRENCY_SYMBOL . number_format($total, 2, ',', '.'): $total;
    $fTotalPago = (!$downloadXls) ? CURRENCY_SYMBOL . number_format($totalPago, 2, ',', '.'): $totalPago;

    $htmlTable .= '  <tr style="font-weight:bold;">';
    $htmlTable .= '    <td colspan="5">TOTAIS:</td>';
    $htmlTable .= '    <td>'.$fTotal.'</td>';
    $htmlTable .= '    <td>&nbsp;</td>';
    $htmlTable .= '    <td>'.$fTotalPago.'</td>';
    $htmlTable .= '    <td colspan="2">&nbsp;</td>';
    $htmlTable .= '  </tr>';

    $htmlTable .= '</tbody>';
    $totalGeral += $total;
    $totalPagoGeral += $totalPago;
}

// TOTAL GERAL
$fTotalGeral = (!$downloadXls) ? CURRENCY_SYMBOL . number_format($totalGeral, 2, ',', '.'): $totalGeral;
$fTotalPagoGeral = (!$downloadXls) ? CURRENCY_SYMBOL . number_format($totalPagoGeral, 2, ',', '.'): $totalPagoGeral;

$htmlTable .= '  <tfoot style="font-weight:bold;" bgcolor="d0d0d0">';
$htmlTable .= '    <tr>';
$htmlTable .= '      <td colspan="5">TOTAIS GERAIS:</td>';
$htmlTable .= '      <td>'.$fTotalGeral.'</td>';
$htmlTable .= '      <td>&nbsp;</td>';
$htmlTable .= '      <td>'.$fTotalPagoGeral.'</td>';
$htmlTable .= '      <td colspan="2">&nbsp;</td>';
$htmlTable .= '    </tr>';
$htmlTable .= '  </tfoot>';
$htmlTable .= '</table>';

// DOWNLOAD XLS
if ($downloadXls) {
    echo $htmlTable;
    return;
}

// REPORT
$html = <<<HTML
    <div id="dvOpenRelatorio">
        <div class="widget-box">
            <div class="widget-title">
                <h5>Relatório</h5>
            </div>
            <div class="widget-content nopadding">
                $htmlTable
            </div>
        </div>
    </div>
HTML;

// PRINT
echo $html;