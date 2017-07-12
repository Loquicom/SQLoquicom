<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Connexion extends ControllerIni{
    
    public function index(){
        $this->load->load_view('connect-form', array('b' => 'test'));
    }
    
}