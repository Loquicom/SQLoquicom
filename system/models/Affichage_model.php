<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Affichage_model extends ModelIni{
    
    /**
     * Recupere la liste des tables et leur nombre d'enregistrement de la BD
     * @return boolean|mixed
     */
    public function getTables(){
        global $_S;
        $requete = $this->db->prepare("Show Tables");
        $requete->execute();
        $result = $requete->fetchAll();
        if($result === false){
            return false;
        }
        //Pour chaque table on recupére le nombre de ligne
        $return = array();
        foreach ($result as $r){
            //Pour chaque table dans la bd
            $requete = $this->db->prepare("Select count(*) From " . $r['Tables_in_' . $_S['db']['name']]);
            $requete->execute();
            $return[$r['Tables_in_' . $_S['db']['name']]] = $requete->fetch()['count(*)'];
        }
        return $return;
    }
    
    
    public function getColumn($table){
        $requete = $this->db->prepare("Select * From " . $table . " limit 0,1");
        $requete->execute();
        $result = $requete->fetch();
        if($result === false){
            return false;
        }
        $return = array();
        foreach ($result as $col => $val){
            $return[] = $col;
        }
        return $return;
    }
    
}