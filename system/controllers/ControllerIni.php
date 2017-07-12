<?php

class ControllerIni{
    
    protected $load;
    protected $model;
            
    function __construct() {
        global $_load;
        $this->load = $_load;
        $this->model = $_load->models;
    }
    
}
