<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Affichage extends ControllerIni{
    
    public function index(){
        global $_db;
        $requete = $_db->prepare("Show Tables");
        $requete->execute();
        var_dump($requete->fetchAll());
    }
    
}