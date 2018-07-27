<?php
defined('FC_INI') or exit('Acces Denied');

class Parametre extends FC_Controller {

    public function __construct() {
        parent::__construct();
        //Si la securite est active
        $this->load->controller('Securite');
        if($this->controller('Securite')->is_secure()){
            if(!$this->controller('Securite')->is_connect()){
                redirect('Securite');
            }
        }
        //$this->session->connect !== false or redirect('Connexion');
    }

    public function index() {
        //Recup de la liste des fichiers des bd sauvegarder
        $this->load->model('Securite_model');
        $files = $this->Securite_model->get_files();
        if($files === false){
            $files = null;
        }
        $page = $this->load->view('params', array('bd' => $files), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function ajx_preference() {
        if ($this->post('color-theme') !== false && $this->post('color-text') !== false && $this->post('title') !== false) {
            setLocalPref($this->post('color-theme'), $this->post('color-text'), $this->post('title'), false);
        }
    }

    public function ajx_supprFile() {
        if($this->post('file') !== false){
            $this->load->model('Securite_model');
            $res =$this->Securite_model->remove_file($this->post('file'));
            if($res !== false){
                echo json_encode(array('etat' => 'ok'));
                exit;
            }
        }
        echo json_encode(array('etat' => 'err'));       
    }

}
