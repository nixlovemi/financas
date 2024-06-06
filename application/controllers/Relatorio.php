<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorio extends MY_Controller {
  public function __construct(){
    MY_Controller::__construct();
  }

  public function index()
  {
    $data = [];
    $this->template->load('template', 'Relatorio/index', $data);
  }

  public function openRelDespesasMes()
  {
    $data = [];
    $this->load->view('Relatorio/openRelDespesasMes', $data);
  }

  public function postRelDespesasMes()
  {
    $this->load->helper('alerts');
    $this->load->helper('utils');
    $data = [
        'downloadXls' => ($this->input->get('frmDownload')) ? (bool) $this->input->get('frmDownload'): false,
        'message' => null,
        'queryArr' => []
    ];

    // INPUTS
    $dtIni = (strlen($this->input->get('frmDataIni')) == 10) ? acerta_data($this->input->get('frmDataIni')): null;
    $dtFim = (strlen($this->input->get('frmDataFim')) == 10) ? acerta_data($this->input->get('frmDataFim')): null;

    // VALIDATION
    if ($dtIni === null || $dtFim === null) {
        $data['message'] = showWarning('Preencha a Data Inicial e a Data Final!');
    }

    if (empty($data['message']) && $dtIni > $dtFim) {
        $data['message'] = showWarning('Data Inicial deve ser menor que a Data Final!');
    }

    // PULL DATA
    if (empty($data['message'])) {
        $this->load->model("Tb_Lancamento");
        $queryArr = $this->Tb_Lancamento->getRelDespesasMesData($dtIni, $dtFim);
        $groupedArr = [];

        // GROUP BY bdp_descricao
        foreach ($queryArr as $row) {
            $category = isset($row['bdp_descricao']) ? $row['bdp_descricao']: 'Categoria não encontrada';
            if (!array_key_exists($category, $groupedArr)) {
                $groupedArr[$category] = [];
            }

            $groupedArr[$category][] = $row;
        }


        $data['queryArr'] = $groupedArr;
    }

    $this->load->view('Relatorio/postRelDespesasMes', $data);
  }
}