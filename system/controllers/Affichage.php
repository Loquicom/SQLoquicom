<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Affichage extends ControllerIni{
    
    public function __construct() {
        parent::__construct();
        $this->load->load_model('Affichage_model');
    }
    
    public function index(){
        $tables = $this->model->affichage_model->getTables();
        $page = $this->load->load_view('list_table', array('tables' => $tables), true);
        $this->load->load_view('webpage', array('body' => $page));
    }
    
    public function table($name){
        (func_num_args() >= 1)?null:$this->errorArgs('table', 1);
        //Code
        $column = $this->model->affichage_model->getColumn($name);
        $page = $this->load->load_view('table', array('nom' => $name, 'column' => $column, 'content' => null), true);
        $this->load->load_view('webpage', array('body' => $page));
    }
    
}