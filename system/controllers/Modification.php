<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Modification extends ControllerIni {

    public function __construct() {
        parent::__construct();
        $this->load->load_model('Modif_model');
    }

    public function ajx_update() {
        
    }

    public function ajx_delete() {
        if (count($_POST) <= 1) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametres invalides'));
            exit;
        }
        $pk = $this->model->modif_model->getPrimary($_POST['table']);
        $i = 0;
        foreach ($_POST['pk-value'] as $val) {
            //On delete chaque ligne envoyer
            $sql = 'Delete From ' . $_POST['table'];
            //On recupere la valeur de toutes les clef primaire
            $val = explode(';', $val);
            //Pour toutes les valeurs
            $j = 0;
            foreach ($val as $value) {
                if ($j == 0) {
                    $sql .= " Where " . $pk[$j] . " = '" . $value . "'";
                } else {
                    $sql .= " And " . $pk[$j] . " = '" . $value . "'";
                }
                $j++;
            }
            //On delete
            $this->model->modif_model->execute($sql);
            $i++;
        }
        //Retour
        echo json_encode(array('etat' => 'ok', 'message' => $i . ' ligne(s) supprimée(s)'));
    }

}
