<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Modif_model extends ModelIni {

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
        $sql = 'Insert into ' . $table . ' (' . $champ . ') Values (' . $value . ');';
        try {
            $requete = $this->db->prepare($sql);
            $requete->execute();
            return 'Ligne ajoutée';
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function update($data, $id, $table) {
        //Parametre a mettre ajour
        $set = '';
        $first = true;
        foreach ($data as $key => $val) {
            $and = " And";
            if($first){
                $and = "";
            }
            if (trim($val) != '') {
                $set .= $and . " " . $key . " = '" . $val . "'";
            }
        }
        //Id des parametre a mettre a jour
        $where = "";
        foreach ($id as $pk => $pkVal){
            $where .= " And " . $pk . " = '" . $pkVal . "'";
        }
        $sql = 'Update ' . $table . ' set' . $set . ' Where 1=1' . $where . ';';
        try {
            $requete = $this->db->prepare($sql);
            $requete->execute();
            return 'Ligne modifiée';
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getLine($table, $id) {
        $sql = "Select * From " . $table . " Where 1=1";
        foreach ($id as $champ => $val) {
            $sql .= " And " . $champ . " = '" . $val . "'";
        }
        $requete = $this->db->prepare($sql);
        $requete->execute();
        $result = $requete->fetchAll();
        return $result[0];
    }

    public function getPrimary($table) {
        $requete = $this->db->prepare("Describe " . $table);
        $requete->execute();
        $result = $requete->fetchAll();
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

    public function execute($sql) {
        $requete = $this->db->prepare($sql);
        $requete->execute();
        return $requete;
    }

}
