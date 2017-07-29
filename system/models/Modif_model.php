<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Modif_model extends ModelIni {

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
    
    public function execute($sql){
        $requete = $this->db->prepare($sql);
        $requete->execute();
        return $requete;
    }

}
