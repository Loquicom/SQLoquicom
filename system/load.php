<?php
(defined('APPLICATION'))?'':exit('Acces denied');

class Loader {

    private static $instance = null;
    public $models;

    private function __construct() {
        $this->models = new LoadModel();
    }

    public static function get_loader() {
        if (self::$instance == null) {
            self::$instance = new Loader();
        }
        return self::$instance;
    }

    private static function in_system() {
        //Recup le nom du script courant
        $path_script = explode('/', $_SERVER['SCRIPT_NAME']);
        if (count($path_script) == 1) {
            //Si qu'une partie on essaye avec des antislash (Windows)
            $path_script = explode('\\', $_SERVER['SCRIPT_NAME']);
        }
        //On regarde le dossier qui est avant le script
        $dir_name = $path_script[count($path_script) - 2];

        return $dir_name == 'system';
    }

    private static function execute_php($filename, $data = array()) {
        if (file_exists($filename . '.php')) {
            ob_start();
            //Création des variables de la vue
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $key => $val) {
                    if (!in_array($key, array('_config', '_db', '_err', '_load'))) {
                        $$key = $val;
                    }
                }
            }
            //Recuperation de la vue
            include $filename . '.php';
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return false;
    }

    public function load_view($name, $data = array(), $return = false) {
        if (trim($name) == '') {
            return false;
        }
        //Création des variables pour la vue si pas return du code (sinon fonction execute_php s'en occupe)
        if (is_array($data) && count($data) > 0 && $return === false) {
            foreach ($data as $key => $val) {
                if (!in_array($key, array('_config', '_db', '_err', '_load'))) {
                    $$key = $val;
                }
            }
        }
        //D'ou le load est appelé
        $path = (self::in_system()) ? 'views/' : 'system/views/';
        //Retour ou affichage
        if ($return) {
            $return = self::execute_php($path . $name, $data);
        } else {
            include $path . $name . '.php';
            $return = true;
        }
        //Fin
        return $return;
    }

    public function load_controller($name) {
        //On determine le chemin du fichier
        if (self::in_system()) {
            $path = 'controllers/';
        } else {
            $path = 'system/controllers/';
        }
        //Mise du nom en minuscule
        $minusName = strtolower($name);
        //On regarde si le fichier existe
        if (file_exists($path . $name . '.php') && !isset($this->$minusName)) {
            //Chargement
            require_once $path . $name . '.php';
            //Instanciation
            $this->$minusName = new $name();
            return true;
        }
        return false;
    }

    public function load_model($name) {
        //On determine le chemin du fichier
        if (self::in_system()) {
            $path = 'models/';
        } else {
            $path = 'system/models/';
        }
        //Mise du nom en minuscule
        $minusName = strtolower($name);
        //On regarde si le fichier existe
        if (file_exists($path . $name . '.php') && $this->models->notSet($name)) {
            //Chargement
            $this->models->add($name, $path . $name . '.php');
            return true;
        }
        return false;
    }

}

class LoadModel{
    
    function notSet($name){
        $minusName = strtolower($name);
        return !isset($this->$minusName);
    }
    
    function add($name, $file){
        $minusName = strtolower($name);
        require $file;
        $this->$minusName = new $name();
    }
    
}