<?php

defined('FC_INI') or exit('Acces Denied');

class Export extends FC_Controller {

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
        $this->load->model('Export_model');
        $this->load->model('Affichage_model');
    }

    private function export_tables() {
        //Recuperation des tables
        $tables = $this->affichage_model->getTables();
        //Creation du script
        $create = '';
        $alter = '';
        foreach ($tables as $table => $nbLignes) {
            $res = $this->export_model->create_script($table);
            $create .= $res['create'] . "\r\n\r\n";
            if (trim($res['alter']) != '') {
                $alter .= $res['alter'] . "\r\n";
            }
        }
        return $create . "\r\n" . $alter;
    }

    private function export_data() {
        //Recuperation des tables
        $tables = $this->affichage_model->getTables();
        $sql = '';
        foreach ($tables as $table => $nbLignes){
            $res = $this->export_model->insert_script($table);
            if(trim($res) != ''){
                $sql .= $res . "\r\n\r\n";
            }
        }
        return rtrim($sql, "\r\n");
    }

    private function generate_sql($sql) {
        $this->load->helper('file');
        if (!file_exists('../data/export/')) {
            mkdir('../data/export/');
        }
        $id = uniqid();
        write_file('../data/export/' . $id, $sql);
        return $id;
    }

    public function download_sql($id, $name = '') {
        if (file_exists('../data/export/' . $id)) {
            //Recup info fichier
            $size = filesize('../data/export/' . $id);
            if (trim($name) == '') {
                $name = $id;
            }
            $name .= '.sql';
            //Envoie
            header("Content-disposition: attachment; filename=$name");
            header("Content-Type: application/force-download");
            header("Content-Transfer-Encoding: application/octet-stream");
            header("Content-Length: $size");
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
            header("Expires: 0");
            readfile('../data/export/' . $id);
            //Suppression du fichier
            @unlink('../data/export/' . $id);
            //Si le dossier export est vide on le supprime
            if(empty(array_diff(scandir('../data/export/'), array('..', '.')))){
                @rmdir('../data/export/');
            }
        } else {
            //Ferme l'onglet ouvert
            echo '<SCRIPT>javascript:window.close()</SCRIPT>';
        }
    }

    public function ajx_create() {
        //Verifie que c'est bien un appel ajax
        if($this->post('ajx') === false){
            exit;
        }
        //Generation du script
        $sql = $this->export_tables();
        //Export
        echo json_encode(array('id' => $this->generate_sql($sql), 'name' => $this->config->get('db', 'name')));
    }

    public function ajx_insert() {
        //Verifie que c'est bien un appel ajax
        if($this->post('ajx') === false){
            exit;
        }
        //Generation du script
        $sql = $this->export_data();
        //Export
        echo json_encode(array('id' => $this->generate_sql($sql), 'name' => $this->config->get('db', 'name')));
    }

    public function ajx_all() {
        //Verifie que c'est bien un appel ajax
        if($this->post('ajx') === false){
            exit;
        }
        //Generation du script
        $sql = $this->export_tables() . $this->export_data();
        //Export
        echo json_encode(array('id' => $this->generate_sql($sql), 'name' => $this->config->get('db', 'name')));
    }

}
