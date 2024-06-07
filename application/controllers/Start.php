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
}
