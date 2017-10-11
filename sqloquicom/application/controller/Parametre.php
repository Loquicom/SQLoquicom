<?php
defined('FC_INI') or exit('Acces Denied');

class Parametre extends FC_Controller {

    public function __construct() {
        parent::__construct();
        $this->session->connect !== false or redirect('Connexion');
    }

    public function index() {
        //Recup de la liste des fichiers des bd sauvegarder
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
        $page = $this->load->view('params', array('bd' => $files), true);
        $this->load->view('webpage', array('body' => $page));
    }

    public function ajx_preference() {
        if ($this->post('color-theme') !== false && $this->post('color-text') !== false && $this->post('title') !== false) {
            setLocalPref($this->post('color-theme'), $this->post('color-text'), $this->post('title'), false);
        }
    }

    public function ajx_supprFile() {
        if($this->post('file') !== false && file_exists('../data/' . $this->post('file'))){
            unlink('../data/' . $this->post('file'));
            echo json_encode(array('etat' => 'ok'));
            exit;
        }
        echo json_encode(array('etat' => 'err'));
    }

}
