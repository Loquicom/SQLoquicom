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
        $create = rtrim(trim(implode('', $sqlExpl)), ",") . "\r\n" . ');';
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

    public function insert_script($table) {
        //Recuperation des donnes
        $this->db->execute("Select * From $table");
        $data = $this->db->result();
        //Si la table n'est pas vide
        if (!empty($data)) {
            //Recuperation champ et valeur
            $first = true;
            $champs = '';
            $values = array();
            foreach ($data as $key => $line) {
                $values[$key] = '(';
                foreach ($line as $champ => $val) {
                    if ($first) {
                        $champs .= $champ . ',';
                    }
                    $values[$key] .= "'" . $val . "',";
                }
                $values[$key] = rtrim($values[$key], ',') . ')';
                if ($first) {
                    $champs = rtrim($champs, ',');
                    $first = false;
                }
            }
            //Création de la requete
            $sql = "Insert into $table ($champs) Values \r\n";
            foreach ($values as $val) {
                $sql .= $val . ",\r\n";
            }
            $sql = rtrim($sql, ",\r\n") . ";";
            return $sql;
        } else {
            return '';
        }
    }

}
