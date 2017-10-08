<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Create_model extends ModelIni {

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
        try {
            $requete = $this->db->prepare($sql);
            $requete->execute();
            return 'Table ' . $data['table'] . ' crée';
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
