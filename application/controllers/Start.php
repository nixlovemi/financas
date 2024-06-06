<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data = [];

    // verifica script de atualizacao
    $scriptPath = "";
    foreach (glob(APPPATH . "cache/script*.sql") as $arquivo) {
      $scriptPath = $arquivo;
    }
    // ==============================

    $data["scriptPath"]   = $scriptPath;
    $this->template->load('template', 'Start/index', $data);
	}

  public function jsonHtmlUpdateBd(){
    $data           = [];
    $retArr         = [];
    $retArr["html"] = "";

    // variaveis ============
    $vScriptPath = $this->input->post('scriptPath');
    // ======================

    $data["scriptPath"] = $vScriptPath;
    $retArr["html"]     = $this->load->view('Start/updateBd', $data, true);
    echo json_encode($retArr);
  }

  public function jsonUpdateDbScript(){
    $retArr         = [];
    $retArr["erro"] = true;
    $retArr["msg"]  = "";

    // variaveis ============
    $vScriptPath = $this->input->post('scriptPath');
    // ======================

    $this->load->model("Database");
    $retScript = $this->Database->execScriptFile($vScriptPath);

    $retArr["erro"] = $retScript["erro"];
    $retArr["msg"]  = $retScript["msg"];

    if(!$retArr["erro"]){
      unlink($vScriptPath);
    }

    echo json_encode($retArr);
  }

  /*public function teste(){
    include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
    $objPHPExcel = new PHPExcel();

    $inputFileName = APPPATH . 'cache/teste.xls';

    //  Read your Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //  Get worksheet dimensions
    $sheet         = $objPHPExcel->getSheet(0);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    $this->load->database();
    $data = [];

    for($i = 2; $i<=603; $i++){
      $despesa      = $sheet->getCellByColumnAndRow(0, $i);
      $tipo         = $sheet->getCellByColumnAndRow(1, $i);
      $parcela      = $sheet->getCellByColumnAndRow(2, $i);
      $vcto         = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP( $sheet->getCellByColumnAndRow(3, $i)->getCalculatedValue() + 1 ));
      $valor        = $sheet->getCellByColumnAndRow(4, $i);
      $categoria    = $sheet->getCellByColumnAndRow(5, $i);
      $subCategoria = $sheet->getCellByColumnAndRow(6, $i);
      $pagamento    = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP( $sheet->getCellByColumnAndRow(8, $i)->getCalculatedValue() + 1 ));
      $valorPg      = $sheet->getCellByColumnAndRow(9, $i);
      $conta        = $sheet->getCellByColumnAndRow(10, $i);
      $obs          = $sheet->getCellByColumnAndRow(11, $i);

      $data[] = array(
        'lan_despesa'    => $despesa,
        'lan_tipo'       => $tipo,
        'lan_vencimento' => $vcto,
        'lan_valor'      => $valor,
        'lan_categoria'  => $categoria,
        'lan_pagamento'  => $pagamento,
        'lan_valor_pago' => $valorPg,
        'lan_conta'      => $conta,
        'lan_observacao' => ($obs != "") ? $categoria . " / " . $obs: $categoria,
      );

      // echo "$despesa|$tipo|$parcela|$vcto|$valor|$categoria|$pagamento|$valorPg|$conta|$obs<br />";
    }

    $this->db->insert_batch('tb_lancamento', $data);

    // $cell = $sheet->getCellByColumnAndRow(0, 1);
    // $val  = $cell->getValue();
    // echo $val;
  }*/
}
