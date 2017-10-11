<?php

defined('FC_INI') or exit('Acces Denied');

class Affichage_model extends FC_Model {

    /**
     * Recupere la liste des tables et leur nombre d'enregistrement de la BD
     * @return boolean|mixed
     */
    public function getTables() {
        global $_S;
        $this->db->execute("Show Tables");
        $result = $this->db->result();
        if ($result === false) {
            return false;
        }
        //Pour chaque table on recupére le nombre de ligne
        $return = array();
        foreach ($result as $r) {
            //Pour chaque table dans la bd
            $this->db->execute("Select count(*) From " . $r['Tables_in_' . $_S['db']['name']]);
            $return[$r['Tables_in_' . $_S['db']['name']]] = $this->db->row()['count(*)'];
        }
        return $return;
    }

    public function getColumn($table) {
        $this->db->execute("Describe " . $table);
        $result = $this->db->result();
        if ($result === false) {
            return false;
        }
        $return = array('list' => array(), 'pk' => array());
        foreach ($result as $champ) {
            //On recupere les clef primaire
            if($champ['Key'] == 'PRI'){
                $return['pk'][] = $champ['Field'];
            }
            $return['list'][] = $champ['Field'];
        }
        return $return;
    }

    public function getNombreLigne($table) {
        $this->db->execute("Select count(*) From " . $table);
        $result = $this->db->row();
        if ($result === false) {
            return false;
        }
        return $result['count(*)'];
    }

    public function getContent($table, $page = 0, $search = "", $limit = 25, $order = "1") {
        //Calcul debut et fin pour la limit
        $debut = $page * $limit;
        //Si la recherche n'est pas vide
        if(trim($search) != ''){
            $col = $this->getColumn($table);
            $sql = "Where 1=0";
            foreach ($col['list'] as $nom){
                $sql .= " Or $nom like '$search%'";
            }
            $search = $sql;
        }
        //Requete
        $this->db->execute("Select * From " . $table . " " . $search . " Order by " . $order . " limit " . $debut . ", " . $limit);
        $result = $this->db->result();
        if ($result === false) {
            return false;
        }
        return $result;
    }
    
    
    public function getInfo($table) {
        $this->db->execute("Describe " . $table);
        $result = $this->db->result();
        if ($result === false) {
            return false;
        }
        return $result;
    }

}
