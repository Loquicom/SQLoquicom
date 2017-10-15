<?php

defined('FC_INI') or exit('Acces Denied');

class Export_model extends FC_Model {

    public function create_script($table) {
        //Recuperation de la requete
        $this->db->execute("Show create table $table");
        $sql = $this->db->row()['Create Table'];
        //On retire les parametre à la fin de la requete
        $sqlExpl = explode(')', $sql);
        unset($sqlExpl[count($sqlExpl) - 1]);
        $sql = implode(')', $sqlExpl);
        //On sépare les constraint du create
        $constraint = array();
        $sqlExpl = explode("CONSTRAINT", $sql);
        if (count($sqlExpl) > 1) {
            $count = count($sqlExpl);
            for ($i = 1; $i < $count; $i++) {
                $constraint[] = 'CONSTRAINT' . $sqlExpl[$i];
                unset($sqlExpl[$i]);
            }
        }
        $create = rtrim(trim(implode('', $sqlExpl)), ",") . "\r\n"  . ');';
        //Generation du Alter table avec les contraintes
        $alter = '';
        if (!empty($constraint)) {
            foreach ($constraint as $c) {
                $alter .= "ALTER TABLE `" . $table . "` ADD " . str_replace("\n", '', trim($c));
                $alter = rtrim($alter, ',') . ';' . "\r\n";
            }
            $alter = rtrim($alter, "\r\n");
        }
        //Retour
        return array('create' => $create, 'alter' => $alter);
    }

}
