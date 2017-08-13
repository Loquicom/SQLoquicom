<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Modification extends ControllerIni {

    public function __construct() {
        parent::__construct();
        $this->load->load_model('Modif_model');
        $this->load->load_model('Affichage_model');
    }

    public function insert() {
        //Recup du nom des colonnes
        $col = $this->model->affichage_model->getColumn($_POST['table']);
        //Info de la table
        $info = $this->model->affichage_model->getInfo($_POST['table']);
        //Chargement de la vue
        $page = $this->load->load_view('insert', array('table' => $_POST['table'], 'col' => $col['list'], 'infos' => $info), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function update() {
        $page = $this->load->load_view('update', array('table' => $_POST['table']), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function ajx_insert() {
        //Recupération du nom de la table
        $table = $_POST['table'];
        unset($_POST['table']);
        //On associe chaque champ a sa ligne
        $insert = array();
        foreach ($_POST as $champ => $vals) {
            $i = 0;
            foreach ($vals as $val) {
                $insert[$i][$champ] = $val;
                $i++;
            }
        }
        //On verifie que tous n'est pas vide pour insérer
        foreach ($insert as $lineNum => $line) {
            $empty = true;
            foreach ($line as $val) {
                if (trim($val) != '') {
                    $empty = false;
                    break;
                }
            }
            //Si tous n'est pas vide on inser
            if (!$empty) {
                echo 'Ligne ' . ($lineNum + 1) . ' : ' . $this->model->modif_model->insert($line, $table) . '<br>';
            }
        }
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
            //On parcours toutes les clef primaire
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

    public function ajx_truncate() {
        if (!isset($_POST['table'])) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre incorrect'));
            exit;
        }
        $sql = "Truncate Table " . $_POST['table'];
        $this->model->modif_model->execute($sql);
        echo json_encode(array('etat' => 'ok', 'message' => 'Table vidée'));
    }

}
