<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Affichage_model extends ModelIni {

    /**
     * Recupere la liste des tables et leur nombre d'enregistrement de la BD
     * @return boolean|mixed
     */
    public function getTables() {
        global $_S;
        $requete = $this->db->prepare("Show Tables");
        $requete->execute();
        $result = $requete->fetchAll();
        if ($result === false) {
            return false;
        }
        //Pour chaque table on recupére le nombre de ligne
        $return = array();
        foreach ($result as $r) {
            //Pour chaque table dans la bd
            $requete = $this->db->prepare("Select count(*) From " . $r['Tables_in_' . $_S['db']['name']]);
            $requete->execute();
            $return[$r['Tables_in_' . $_S['db']['name']]] = $requete->fetch()['count(*)'];
        }
        return $return;
    }

    public function getColumn($table) {
        $requete = $this->db->prepare("Describe " . $table);
        $requete->execute();
        $result = $requete->fetchAll();
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
        $requete = $this->db->prepare("Select count(*) From " . $table);
        $requete->execute();
        $result = $requete->fetch();
        if ($result === false) {
            return false;
        }
        return $result['count(*)'];
    }

    public function getContent($table, $page = 0, $limit = 25, $order = "1") {
        //Calcul debut et fin pour la limit
        $debut = $page * $limit;
        //Requete
        $requete = $this->db->prepare("Select * From " . $table . " Order by " . $order . " limit " . $debut . ", " . $limit);
        $requete->execute();
        $result = $requete->fetchAll();
        if ($result === false) {
            return false;
        }
        return $result;
    }
    
    
    public function getInfo($table) {
        $requete = $this->db->prepare("Describe " . $table);
        $requete->execute();
        $result = $requete->fetchAll();
        if ($result === false) {
            return false;
        }
        return $result;
    }

}
