<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Connexion extends ControllerIni{
    
    public function index(){
        $page = $this->load->load_view('connect-form', null, true);
        $this->load->load_view('webpage', array('body' => $page));
    }
    
    public function deco(){
        global $_S;
        global $_db;
        global $_config;
        unset($_S['db']);
        $_db = null;
        header('Location: ' . $_config['web_root']);
    }
    
}