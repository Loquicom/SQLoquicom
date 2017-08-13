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
        if (((int) $pagine) != $pagine) {
            $pagine = ((int) $pagine) + 1;
        }
        //Chargement de la page
        $page = $this->load->load_view('table', array('nom' => $name, 'column' => $column['list'], 'pk' => $column['pk'], 'pagine' => $pagine, 'limit' => $limit), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function ajx_tableContent() {
        if (!isset($_POST['table']) && isset($_POST['page'])) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Données incorrect</h3></td></tr>');
        }
        //On recupére les données
        $lines = $this->model->affichage_model->getContent($_POST['table'], $_POST['page'] - 1, (isset($_POST['limit'])) ? $_POST['limit'] : 25, (isset($_POST['order'])) ? $_POST['order'] : 1);
        if ($lines === null || empty($lines)) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Table vide</h3></td></tr>');
        }
        //Création du tableau html
        $return = '';
        foreach ($lines as $line) {
            $return .= '<tr>';
            //Si il y a une primary key
            if (isset($_POST['pk']) && is_array($_POST['pk']) && !empty($_POST['pk'])) {
                //Creation de la valeur de la clef primaire
                $pk = '';
                foreach ($_POST['pk'] as $clef) {
                    $pk .= $line[$clef] . ';';
                }
                $pk = rtrim($pk, ';');
                $return .= '<td class="center"><input class="line_action" type="checkbox" name="pk-value[]" value="' . $pk . '"></td>';
            }
            foreach ($line as $val) {
                $return .= '<td>' . $val . '</td>';
            }
            $return .= '</tr>';
        }
        echo $return;
    }

    public function ajx_tableInfo() {
        //Verification
        if (!isset($_POST['table'])) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Données incorrect</h3></td></tr>');
        }
        //Recup des infos
        $infos = $this->model->affichage_model->getInfo($_POST['table']);
        if ($infos === null || empty($infos)) {
            exit('<tr><td colspan="100%" style="text-align: center;"><h3>Aucune information</h3></td></tr>');
        }
        //Generation du html
        $return = '';
        foreach ($infos as $info){
            $return .= '<tr>';
            foreach ($info as $i){
                $return .= '<td>' . $i . '</td>';
            }
            $return .= '</tr>';
        }
        //On change clef primaire et etrangere par PK et FK
        $return = str_replace('MUL', 'FK', str_replace('PRI', 'PK', $return));
        echo $return;
    }

}
