<?php

defined('FC_INI') or exit('Acces Denied');

class Structure extends FC_Controller {

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Structure_model');
    }

    public function create() {
        $page = $this->load->view('create', null, true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function alter($table) {
        $page = $this->load->view('alter', array("table" => $table), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function ajx_create() {
        if (!($this->post('table') !== false && trim($this->post('table')) != '' && trim($this->post('chp', 0, 'nom')) != '' && trim($this->post('chp', 0, 'type')) != '')) {
            echo 'Donn&eacute;es manquantes';
            exit;
        }
        echo $this->structure_model->create($_POST);
    }

    public function ajx_alter() {
        if (!($this->post('chp') !== false && $this->post('table') !== false)) {
            echo 'Donn&eacute;es manquantes';
            exit;
        }
        $retour = '';
        foreach ($this->post('chp') as $num => $chp) {
            $retour .= "Alter " . ($num + 1) . " : ";
            if ($chp['action'] == 1) {
                if (!($this->post('nom') !== false && $this->post('type') !== false && $this->post('defaut') !== false && trim($this->post('type')) != '')) {
                    $retour .= $this->structure_model->alter_modif($this->post('table'), $chp);
                } else {
                    $retour .= "Parametre incorrect";
                }
            } else if ($chp['action'] == 2) {
                if (!($this->post('nom') !== false && $this->post('type') !== false && $this->post('defaut') !== false && trim($this->post('nom')) != '' && trim($this->post('type')) != '')) {
                    $retour .= $this->structure_model->alter_create($this->post('table'), $chp);
                } else {
                    $retour .= "Parametre incorrect";
                }
            } else if ($chp['action'] == 3) {
                if (!($this->post('nom') !== false)) {
                    $retour .= $this->structure_model->alter_delete($this->post('table'), $chp);
                } else {
                    $retour .= "Parametre incorrect";
                }
            } else {
                $retour .= 'Action inconnue';
            }
            $retour .= "<br>";
        }
        echo trim($retour, "<br>");
    }

    public function ajx_drop() {
        if ($this->post('table') === false) {
            echo json_encode(array('etat' => 'err', 'message' => 'Parametre incorrect'));
            exit;
        }
        $this->structure_model->drop($this->post('table'));
        echo json_encode(array('etat' => 'ok', 'message' => 'Table supprimée'));
    }

}
