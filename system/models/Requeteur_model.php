<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Requeteur_model extends ModelIni {

    public function execute($sql) {
        try {
            $requete = $this->db->prepare($sql);
            $requete->execute();
            if ($requete->columnCount() != 0) {
                return $requete->fetchAll();
            } else {
                return true;
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
