<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Affichage extends ControllerIni {

    public function __construct() {
        parent::__construct();
        $this->load->load_model('Affichage_model');
    }

    public function index() {
        $tables = $this->model->affichage_model->getTables();
        $page = $this->load->load_view('list_table', array('tables' => $tables), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function table($name, $limit = 25) {
        $this->verifArgs(1, func_get_args(), __METHOD__);
        //Code
        $column = $this->model->affichage_model->getColumn($name);
        //Nombre de pagine a avoir
        $pagine = $this->model->affichage_model->getNombreLigne($name) / $limit;
        if(((int)$pagine) != $pagine){
            $pagine = ((int)$pagine) + 1;
        }
        //Chargement de la page
        $page = $this->load->load_view('table', array('nom' => $name, 'column' => $column, 'pagine' => $pagine, 'limit' => $limit), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function ajx_tableContent() {
        if (!isset($_POST['table']) && isset($_POST['page'])) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Données incorrect</h3></td></tr>');
        }
        //On recupére les données
        $lines = $this->model->affichage_model->getContent($_POST['table'], $_POST['page'] - 1, (isset($_POST['limit'])) ? $_POST['limit'] : 25);
        if ($lines === null || empty($lines)) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Table vide</h3></td></tr>');
        }
        //Création du tableau html
        $return = '';
        foreach ($lines as $line) {
            $return .= '<tr>';
            foreach ($line as $val) {
                $return .= '<td>' . $val . '</td>';
            }
            $return .= '</tr>';
        }
        echo $return;
    }

}
