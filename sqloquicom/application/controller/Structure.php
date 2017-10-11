<?php
defined('FC_INI') or exit('Acces Denied');

class Structure extends FC_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Structure_model');
    }
    
    public function create(){
        $page = $this->load->view('create', null, true);
        $this->load->view('webpage', array('body' => $page));
    }
    
    public function ajx_create(){
        if(!($this->post('table') !== false && trim($this->post('table')) != '' && trim($this->post('chp', 0, 'nom')) != '' && trim($this->post('chp', 0, 'type')) != '')){
            echo 'Donn&eacute;es manquantes';
            exit;
        }
        echo $this->structure_model->create($_POST);
    }
    
    public function ajx_drop() {
        if ($this->post('table') === false) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre incorrect'));
            exit;
        }
        $this->structure_model->drop($this->post('table'));
        echo json_encode(array('etat' => 'ok', 'message' => 'Table supprimée'));
    }
    
}
