<?php

class ControllerIni{
    
    protected $load;
    protected $model;
            
    function __construct() {
        global $_load;
        $this->load = $_load;
        $this->model = $_load->models;
    }
    
    function errorArgs($name = '', $nb = 0){
        exit("Erreur " . $name . $nb);
    }
    
}
