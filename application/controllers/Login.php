<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
  public function __construct(){
    CI_Controller::__construct();
  }

	public function index(){
		$this->load->view('Login/index');
	}

  public function postLogin(){
    $user     = $this->input->post('user');
    $password = $this->input->post('password');

    $this->load->model('Tb_Usuario');
    $arrInfo             = [];
    $arrInfo["user"]     = $user;
    $arrInfo["password"] = $password;
    $arrRet              = $this->Tb_Usuario->checkLogin($arrInfo);

    $isError  = $arrRet["error"];
    $isActive = $arrRet["usu_ativo"];

    if($isError || !$isActive){
      $this->load->helper('alerts');

      $data = [];
      $data["warningMsg"] = showWarning("Usuário ou senha inválidos!");

  		$this->load->view('Login/index', $data);
    } else {
      $sessionData = $arrRet;
      $this->session->set_userdata('logged_in', $sessionData);

      $redirect = base_url() . "Start";
      header("location:$redirect");
    }
  }

  public function logout(){
    $sessionData = [];
    $this->session->set_userdata('logged_in', $sessionData);
    $this->load->helper('alerts');

    $data = [];
    $data["warningMsg"] = showInfo("Você foi desconectado com sucesso!");

    $this->load->view('Login/index', $data);
  }
}
