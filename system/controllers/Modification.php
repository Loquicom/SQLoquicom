<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Modification extends ControllerIni {

    public function __construct() {
        parent::__construct();
        $this->load->load_model('Modif_model');
    }

    public function ajx_delete() {
        if (count($_POST) <= 1) {
            exit;
        }
        $pk = $this->model->modif_model->getPrimary($_POST['table']);
        foreach ($_POST['pk-value'] as $val) {
            //On delete chaque ligne envoyer
            $sql = 'Delete From ' . $_POST['table'];
            //On recupere la valeur de toutes les clef primaire
            $val = explode(';', $val);
            //Pour toutes les valeurs
            $i = 0;
            foreach ($val as $value) {
                if ($i == 0) {
                    $sql .= " Where " . $pk[$i] . " = '" . $value . "'";
                } else {
                    $sql .= " And " . $pk[$i] . " = '" . $value . "'";
                }
                $i++;
            }
            //On delete
            $this->model->modif_model->execute($sql);
        }
    }

}
