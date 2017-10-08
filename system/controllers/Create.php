<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Create extends ControllerIni {
    
    public function __construct() {
        parent::__construct();
        $this->load->load_model('Create_model');
    }

    public function index(){
        $page = $this->load->load_view('create', null, true);
        $this->load->load_view('webpage', array('body' => $page));
    }
    
    public function ajx_create(){
        echo $this->model->create_model->create($_POST);
    }
    
}
