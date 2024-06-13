<?php
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
    }

    private function is_logged_in()
    {
      $sessionData = $this->session->get_userdata('logged_in');
      $loggedData  = (isset($sessionData["logged_in"])) ? $sessionData["logged_in"]: array();

      if( count($loggedData) <= 0 ){
        $redirect = base_url() . "Login";
        header("location:$redirect");
        return;
      } else {
        $vController = $this->router->fetch_class();
        $vAction     = $this->router->fetch_method();

        // array do menu do template
        $this->load->model('Tb_Menu');
        $vArrMenu = $this->Tb_Menu->getMenuArr();
        // =========================

        // variaveis do template
        $this->template->set('arrMenu', $vArrMenu);
        $this->template->set('controller', $vController);
        $this->template->set('action', $vAction);
        $this->template->set('username', $loggedData["usu_nome"]);
        // =====================

        return true;
      }
    }
}
