<?php
defined('FC_INI') or exit('Acces Denied');

class Donnee_model extends FC_Model {
    
    public function insert($data, $table) {
        $champ = '';
        $value = '';
        foreach ($data as $key => $val) {
            if (trim($val) != '') {
                $champ .= $key . ',';
                $value .= "'$val'" . ',';
            }
        }
        $champ = rtrim($champ, ',');
        $value = rtrim($value, ',');
        $sql = 'Insert into ' . $table . ' (' . $champ . ') Values (' . $this->db->protect($value) . ');';
        $retour = $this->db->execute($sql, true);
        if (!is_string($retour)){
            return 'Ligne ajoutée';
        } else {
            return $retour;
        }
    }
    
    public function update($data, $id, $table) {
        //Parametre a mettre ajour
        $set = '';
        $first = true;
        foreach ($data as $key => $val) {
            $and = ", ";
            if ($first) {
                $and = "";
                $first = false;
            }
            if (trim($val) != '') {
                $set .= $and . " " . $key . " = '" . $this->db->protect($val) . "'";
            } else {
                $set .= $and . " " . $key . " = null";
            }
        }
        //Id des parametre a mettre a jour
        $where = "";
        foreach ($id as $pk => $pkVal) {
            $where .= " And " . $pk . " = '" . $pkVal . "'";
        }
        $sql = 'Update ' . $table . ' set' . $set . ' Where 1=1' . $where . ';';
        $retour = $this->db->execute($sql, true);
        if(!is_string($retour)) {
            return 'Ligne modifiée';
        } else {
            return $retour;
        }
    }
    
    public function delete($table, $keysValue) {
        $pk = $this->getPrimary($table);
        $i = 0;
        foreach ($keysValue as $val) {
            //On delete chaque ligne envoyer
            $sql = 'Delete From ' . $table;
            //On recupere la valeur de toutes les clef primaire
            $val = explode(';', $val);
            //Pour toutes les valeurs
            $j = 0;
            //On parcours toutes les clef primaire
            foreach ($val as $value) {
                if ($j == 0) {
                    $sql .= " Where " . $pk[$j] . " = '" . $value . "'";
                } else {
                    $sql .= " And " . $pk[$j] . " = '" . $value . "'";
                }
                $j++;
            }
            //On delete
            $this->db->execute($sql);
            $i++;
        }
    }
    
    public function truncate($table){
        $sql = "Truncate Table " . $table;
        $this->db->execute($sql);
    }
    
    public function getPrimary($table) {
        $this->db->execute("Describe " . $table);
        $result = $this->db->result();
        if ($result === false) {
            return false;
        }
        $return = array();
        foreach ($result as $champ) {
            //On recupere les clef primaire
            if ($champ['Key'] == 'PRI') {
                $return[] = $champ['Field'];
            }
        }
        return $return;
    }
    
    public function getLine($table, $id) {
        $sql = "Select * From " . $table . " Where 1=1";
        foreach ($id as $champ => $val) {
            $sql .= " And " . $champ . " = '" . $val . "'";
        }
        $this->db->execute($sql);
        $result = $this->db->result();
        return $result[0];
    }
    
}
