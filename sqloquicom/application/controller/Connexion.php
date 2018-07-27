<?php

defined('FC_INI') or exit('Acces Denied');

class Connexion extends FC_Controller {

    public function __construct(){
        parent::__construct();
        //Si la securite est active
        $this->load->controller('Securite');
        if($this->controller('Securite')->is_secure()){
            if(!$this->controller('Securite')->is_connect()){
                redirect('Securite');
            }
        }
    }

    public function index($err = '') {
        //Chargement bibliotheque
        $this->load->library('Cookie');
        //Gestion affichage erreur
        $err = str_replace('%20', ' ', $err);
        //Si on est deja connecté
        if ($this->session->connect !== false) {
            //Redirection
            redirect('Affichage');
        }

        //Si il y a une connexion à la base
        if ($this->post('host') !== false && $this->post('name') !== false && $this->post('usr') !== false && $this->post('pass') !== false) {
            $this->session->db = array('host' => $this->post('host'), 'name' => $this->post('name'), 'usr' => $this->post('usr'), 'pass' => $this->post('pass'));
            //Sauvegarde ou non de la base dans un fichier
            if ($this->post('keep') !== false) {
                $this->load->model('Securite_model');
                $this->Securite_model->add_file($_POST);
            }
            //Sauvegarde de la base dans un cookie
            if ($this->post('keep-cookie') !== false) {
                //On recup le numero du cookie
                $i = 0;
                $cookie = $this->cookie->get('db_save');
                if ($cookie !== false) {
                    foreach ($cookie as $val) {
                        $i++;
                    }
                }
                $this->cookie->add('db_save[' . $i . ']', saveDatabaseContent($this->post('host'), $this->post('name'), $this->post('usr'), $this->post('pass')));
            }
            //Rechargement mpage pour prendre en compte la connexion
            redirect('Connexion');
        }

        //Recup de la liste des fichiers des bd sauvegarder
        $this->load->model('Securite_model');
        $files = $this->Securite_model->get_files();
        if($files === false){
            $files = null;
        }
        //Recup liste des fichiers en cookie
        $cookie = $this->cookie->get('db_save');
        if ($cookie !== false) {
            foreach ($cookie as $num => $db) {
                $decode = base64_decode($db);
                $content = explode("\r\n", $decode);
                if (md5($content[1] . '(-)' . $content[2] == $content[0])) {
                    $files[] = 'navigateur|' . $num . '| ' . $content[1] . '(-)' . $content[2];
                }
            }
        }

        //Affichage
        $page = $this->load->view('connect', array('db' => $files, 'err' => $err), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function deco() {
        $this->session->remove('db');
        $this->session->connect = false;
        redirect('Connexion');
    }

    public function ajx_readDB() {
        //Si on reçoit un post pour recup les infos de la base
        if ($this->post('db') !== false) {
            //Cookie ou fichier
            if (strpos($this->post('db'), 'navigateur') === false) {
                //Lecture des infos du fichier
                $this->load->model('Securite_model');
                $fileName = $this->post('db');
                $file = $this->Securite_model->get_file($fileName);
                if($file !== false){
                    $file = $file[$this->post('db')];
                    //Envoie des infos
                    echo json_encode(array('etat' => 'ok', 'hote' => $file['host'], 'base' => $file['name'], 'user' => $file['usr'], 'mdp' => $file['pass']));
                    exit;
                }
                //Si erreur
                echo json_encode(array('etat' => 'err'));
                exit;
            }
            //Cookie
            else {
                $this->load->library('Cookie');
                $cookie = $this->cookie->get('db_save');
                $expl = explode('|', $this->post('db'));
                list($md5, $host, $name, $usr, $pass) = explode("\r\n", base64_decode($cookie[$expl[1]]));
                //Envoie des infos
                echo json_encode(array('etat' => 'ok', 'hote' => $host, 'base' => $name, 'user' => $usr, 'mdp' => $pass));
                exit;
            }
        }
    }

}
