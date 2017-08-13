<?php

(defined('APPLICATION')) ? '' : exit('Acces denied');

class Connexion extends ControllerIni {

    public function index($err = '') {
        //Si on reçoit un post pour recup les infos de la base
        if (isset($_POST['db'])) {
            //Lecture des infos du fichier
            if (file_exists('data/db/' . $_POST['db'])) {
                //Extraction des données
                $content = base64_decode(explode("\r\n", file_get_contents('data/db/' . $_POST['db']))[0]);
                list($md5, $host, $name, $usr, $pass) = explode("\r\n", $content);
                //Verification de l'intégrité du fichier
                if ($md5 != md5(str_replace('.dat', '', $_POST['db']))) {
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

        //Recup de la liste des fichiers des bd sauvegarder
        $files = null;
        if (file_exists('data/db/')) {
            $files = array_diff(scandir('data/db'), array('..', '.', '.htaccess'));
            //On verifie que les fichiers ne sont pas modifié
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $content = explode("\r\n", file_get_contents('data/db/' . $file));
                    //Si le fichier n'est pas bon on le supprile
                    if ($content[1] !== md5($content[0])) {
                        unlink('data/db/' . $file);
                    }
                }
                if (count(array_diff(scandir('data/db'), array('..', '.', '.htaccess'))) < 1) {
                    $files = null;
                }
            } else {
                $files = null;
            }
        }
        $page = $this->load->load_view('connect-form', array('db' => $files, 'err' => $err), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function deco() {
        global $_S;
        global $_db;
        global $_config;
        unset($_S['db']);
        $_db = null;
        header('Location: ' . $_config['web_root']);
    }

}
