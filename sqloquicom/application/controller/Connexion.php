<?php

defined('FC_INI') or exit('Acces Denied');

class Connexion extends FC_Controller {

    public function index($err = '') {
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
                saveDatabase($this->post('host'), $this->post('name'), $this->post('usr'), $this->post('pass'), true);
            }
            //Rechargement mpage pour prendre en compte la connexion
            redirect('Connexion');
        }

        //Recup de la liste des fichiers des bd sauvegarder
        $files = null;
        if (file_exists('../data/')) {
            $files = array_diff(scandir('../data/'), array('..', '.', '.htaccess'));
            //On verifie que les fichiers ne sont pas modifié
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $content = explode("\r\n", file_get_contents('../data/' . $file));
                    //Si le fichier n'est pas bon on le supprile
                    if ($content[1] !== md5($content[0])) {
                        unlink('../data/' . $file);
                    }
                }
                if (count(array_diff(scandir('../data/'), array('..', '.', '.htaccess'))) < 1) {
                    $files = null;
                }
            } else {
                $files = null;
            }
        }
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
            //Lecture des infos du fichier
            if (file_exists('../data/' . $this->post('db'))) {
                //Extraction des données
                $content = base64_decode(explode("\r\n", file_get_contents('../data/' . $this->post('db')))[0]);
                list($md5, $host, $name, $usr, $pass) = explode("\r\n", $content);
                //Verification de l'intégrité du fichier
                if ($md5 != md5(str_replace('.dat', '', $this->post('db')))) {
                    echo json_encode(array('etat' => 'err'));
                    exit;
                }
                //Envoie des infos
                echo json_encode(array('etat' => 'ok', 'hote' => $host, 'base' => $name, 'user' => $usr, 'mdp' => $pass));
                exit;
            }
            echo json_encode(array('etat' => 'err'));
            exit;
        }
    }

}
