<?php

class ModelIni{
    
    protected $db;
    
    function __construct() {
        global $_db;
        $this->db = $_db;
    }
    
}
