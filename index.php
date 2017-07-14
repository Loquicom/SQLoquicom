<?php

//Ini pour initialiser les fonctionnalités necessaire
require_once '_ini.php';

//Loader
require_once 'system/load.php';
$_load = Loader::get_loader();


//Si on reçoit des parametres en POST on les traites
if (isset($_POST['host']) && isset($_POST['name']) && isset($_POST['usr']) && isset($_POST['pass'])) {
    $_S['db'] = $_POST;
    //Si keep est present on sauvegarde les données dans un fichier dans data
    if (isset($_POST['keep'])) {
        
    }
}
//Connxion à la BD
require_once 'system/Database.param.php';

//Si la connexion n'est pas faite avec la BD
if (!$_db) {
    //Soit juste pas d'infos
    if ($_err == null) {
        //On apelle le controller de la connexion
        $_load->load_controller('Connexion');
        $_load->connexion->index();
        exit;
    }
    //Soit une erreur
    else {
        //Affichage de l'erreur de connexion et suppression des infos pour recommencer la connexion
        echo $_err;
        $_err = null;
        session_destroy();
        exit;
    }
}


if (!isset($_GET['r']) || empty($_GET['r']) || trim($_GET['r']) == '') {
    $_load->load_controller('Affichage');
    $_load->affichage->index();
} else {
    //On decoupe l' adresse
    $path = explode('/', $_GET['r']);
    if (count($path) > 0) {
        //On prepare l'appel de la fonction
        $name = uniqid(md5(mt_rand(1, 1000000000)), true) . '.php';
        $file = fopen('system/tmp/' . $name, 'w');
        $content = "<?php \r\n";
        //Passage tous les warning, notice, ... en exception pour pouvoir les attraper avec un try catch
        $content .= 'try{' . "\r\n";
        $content .= 'set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {if (0 === error_reporting()) {return false;}throw new ErrorException($errstr, 0, $errno, $errfile, $errline);});' . "\r\n";
        $content .= '$_load->' . "load_controller('" . $path[0] . "'); \r\n";
        $content .= '$_load->' . strtolower($path[0]) . "->" . ((isset($path[1])) ? $path[1] : 'index') . '(';
        //Si il y a des parametres
        $var = '';
        if (count($path) > 2) {
            for ($i = 2; $i < count($path); $i++) {
                $varName = randomString();
                $$varName = $path[$i];
                $var .= '$' . $varName . ',';
            }
            $var = rtrim($var, ",");
        }
        $content .= $var . ');' . "\r\n";
        $content .= 'restore_error_handler();' . "\r\n";
        $content .= '} catch(Exception $excep) {echo "Erreur";}';
        //Ecriture
        fwrite($file, $content);
        //Fermeture
        fclose($file);

        try {
            //On require le fichier qui va appeler la fonction
            require 'system/tmp/' . $name;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        //Suppr du fichier
        unlink('system/tmp/' . $name);
    } else {
        
    }
}


/*
 * Create a random string
 * @author  XEWeb <>
 * @param $length the length of the string to create
 * @return $str the string
 */

function randomString($length = 10) {
    $str = "";
    $characters = array_merge(range('A', 'Z'), range('a', 'z'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}
