<?php

defined('FC_INI') or exit('Acces Denied');

class Donnee extends FC_Controller {

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Affichage_model');
        $this->load->model('Donnee_model');
    }

    public function insert() {
        if ($this->post('table') === false) {
            redirect('Affichage');
        }
        //Recup du nom des colonnes
        $col = $this->affichage_model->getColumn($this->post('table'));
        //Info de la table
        $info = $this->affichage_model->getInfo($this->post('table'));
        //Chargement de la vue
        $page = $this->load->view('insert', array('table' => $this->post('table'), 'col' => $col['list'], 'infos' => $info), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function update() {
        if ($this->post('table') === false) {
            redirect('Affichage');
        }
        //Recup du nom des colonnes
        $col = $this->affichage_model->getColumn($this->post('table'));
        //Info de la table
        $info = $this->affichage_model->getInfo($this->post('table'));
        //Recup des clef primaires
        $pk = $this->donnee_model->getPrimary($this->post('table'));
        //On associe les clef avec les valeurs des lignes a modifier
        $lignes = array();
        foreach ($_POST['pk-value'] as $pkValue) {
            $lignes[] = $this->donnee_model->getLine($this->post('table'), array_combine($pk, explode(';', $pkValue)));
        }
        //Chargement de la vue
        $page = $this->load->view('update', array('table' => $this->post('table'), 'col' => $col['list'], 'infos' => $info, 'lines' => $lignes), true);
        $this->load->view('webpage', array('body' => $page));
    }

    /* ===== Ajax ===== */

    public function ajx_insert() {
        if ($this->post('table') === false) {
            echo 'Erreur';
            exit;
        }
        //Recupération du nom de la table
        $table = $this->post('table');
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
            //Si tous n'est pas vide on insert
            if (!$empty) {
                echo 'Ligne ' . ($lineNum + 1) . ' : ' . $this->donnee_model->insert($line, $table) . '<br>';
            } else {
                echo 'Ligne ' . ($lineNum + 1) . ' : Aucunne données<br>';
            }
        }
    }

    public function ajx_update() {
        if ($this->post('table') === false || $this->post('pk') === false) {
            echo 'Erreur';
            exit;
        }
        //Recupération du nom de la table
        $table = $this->post('table');
        unset($_POST['table']);
        //Recup des clef primaires et de leur valeur
        $pk = $this->post('pk');
        unset($_POST['pk']);
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
                echo 'Ligne ' . ($lineNum + 1) . ' : ' . $this->donnee_model->update($line, $pk[$lineNum], $table) . '<br>';
            } else {
                echo 'Ligne ' . ($lineNum + 1) . ' : Aucunne données<br>';
            }
        }
    }
    
    public function ajx_delete() {
        if (count($_POST) <= 1) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametres invalides'));
            exit;
        }
        $this->donnee_model->delete($this->post('table'), $this->post('pk-value'));
        //Retour
        echo json_encode(array('etat' => 'ok', 'message' => count($_POST) . ' ligne(s) supprimée(s)'));
    }
    
    public function ajx_truncate() {
        if ($this->post('table') === false) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre incorrect'));
            exit;
        }
        $this->donnee_model->truncate($this->post('table'));
        echo json_encode(array('etat' => 'ok', 'message' => 'Table vidée'));
    }

}
