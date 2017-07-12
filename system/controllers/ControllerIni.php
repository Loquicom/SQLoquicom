<?php

class ControllerIni{
    
    protected $load;
    
    function __construct() {
        global $_load;
        $this->load = $_load;
    }
    
}
