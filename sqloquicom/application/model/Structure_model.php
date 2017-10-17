<?php

defined('FC_INI') or exit('Acces Denied');

class Structure_model extends FC_Model {

    public function create($data) {
        //Ajoute des champs et des clefs etrangères
        $create = 'Create Table ' . $data['table'] . '(';
        $foreign = ',';
        foreach ($data['chp'] as $champ) {
            $defaut = (trim($champ['defaut']) != '') ? 'Default ' . $champ['defaut'] : '';
            $create .= ' ' . $champ['nom'] . ' ' . $champ['type'] . $champ['null'] . $champ['ai'] . ' ' . $defaut . ',';
            if (trim($champ['fk']) != '' && count(explode('(', $champ['fk'])) == 2) {
                $foreign .= ' Constraint fk_' . $data['table'] . '_' . (explode('(', $champ['fk'])[0]) . ' Foreign Key (' . $champ['nom'] . ') References ' . $champ['fk'] . ',';
            }
        }
        //Calcul de la clef primaire
        $primary = '';
        foreach ($data['pk'] as $num => $bool) {
            if ((bool) $bool) {
                $primary .= $data['chp'][$num]['nom'] . ',';
            }
        }
        if (trim($primary) != '') {
            $primary = ', Constraint pk_' . $data['table'] . ' Primary Key (' . rtrim($primary, ',') . '),';
        } else {
            $primary = ',';
        }
        //Reconstitution de la requete
        $sql = rtrim($create, ',') . rtrim($primary, ',') . rtrim($foreign, ',') . ' );';
        //Execution
        $retour = $this->db->execute($sql, true);
        if (!is_string($retour)) {
            return 'Table ' . $data['table'] . ' crée';
        } else {
            return $retour;
        }
    }

    public function drop($table) {
        $sql = "Drop Table " . $table . " CASCADE";
        $this->db->execute($sql);
    }

    public function alter_modif($table, $data) {
        $defaut = (trim($data['defaut']) != '') ? " Default '" . $data['defaut'] . "'"  : '';
        $sql = "Alter Table " . $table . " Modify " . $data['nom'] . " " . $data['type'] . $defaut;
        $retour = $this->db->execute($sql, true);
        if (!is_string($retour)) {
            return 'Champ ' . $data['nom'] . ' modifié';
        } else {
            return $retour;
        }
    }

    public function alter_create($table, $data) {
        $defaut = (trim($data['defaut']) != '') ? " Default '" . $data['defaut'] . "'" : '';
        $sql = "Alter Table " . $table . " Add " . $data['nom'] . " " . $data['type'] . $defaut;
        $retour = $this->db->execute($sql, true);
        if (!is_string($retour)) {
            return 'Champ ' . $data['nom'] . ' créé';
        } else {
            return $retour;
        }
    }

    public function alter_delete($table, $data) {
        $sql = "Alter Table " . $table . " Drop " . $data['nom'];
        $retour = $this->db->execute($sql, true);
        if (!is_string($retour)) {
            return 'Champ ' . $data['nom'] . ' supprimé';
        } else {
            return $retour;
        }
    }

}
