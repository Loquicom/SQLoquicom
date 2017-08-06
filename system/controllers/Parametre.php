<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');

class Parametre extends ControllerIni {

    public function index() {
        //Recup de la liste des fichiers des bd sauvegarder
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
        $page = $this->load->load_view('params', array('bd' => $files), true);
        $this->load->load_view('webpage', array('body' => $page));
    }

    public function ajx_preference() {
        if (isset($_POST['color-theme']) && isset($_POST['color-text']) && isset($_POST['title'])) {
            setLocalPref($_POST['color-theme'], $_POST['color-text'], $_POST['title'], false);
        }
    }

    
    public function ajx_supprFile() {
        var_dump(file_exists('data/db/' . $_POST['file']));
        if(isset($_POST['file']) && file_exists('data/db/' . $_POST['file'])){
            unlink('data/db/' . $_POST['file']);
            echo json_encode(array('etat' => 'ok'));
            exit;
        }
        echo json_encode(array('etat' => 'err'));
    }

}
