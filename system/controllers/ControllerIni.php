<?php

class ControllerIni{
    
    protected $load;
    protected $model;
            
    function __construct() {
        global $_load;
        $this->load = $_load;
        $this->model = $_load->models;
    }
    
    function verifArgs($num, $args, $method){
        //Verifie qu'il y a le bon nombre d'argument
        if(!(count($args) >= $num)){
            //Erreur
            exit('ErreurArg : ' . $method);
        }
    }
    
    
}
