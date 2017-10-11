<?php

defined('FC_INI') or exit('Acces Denied');

class Requeteur_model extends FC_Model {

    public function execute($sql) {
        $requete = $this->db->execute($sql, true, true);
        if (!is_string($requete)) {
            if ($requete->columnCount() != 0) {
                return $requete->fetchAll();
            } else {
                return true;
            }
        } else {
            return $requete;
        }
    }

}
