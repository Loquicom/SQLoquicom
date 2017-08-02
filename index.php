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
    //On s'occupe du routage avec le parametre $_GET['r']
    $path = explode('/', $_GET['r']);
    //Chargement du controller
    if ($_load->load_controller($path[0]) === false) {
        
    }
    $path[0] = strtolower($path[0]);
    //Chargement de la methode
    $methode = (isset($path[1])) ? $path[1] : 'index';
    if (!method_exists($_load->$path[0], $methode)) {
        
    }
    //Avec ou sans parametre
    if (count($path) > 2 && trim($path[2]) != '' && $path[2] != null) {
        //Avec
        $params = $path;
        unset($params[0]);
        unset($params[1]);
        call_user_func_array(array($_load->$path[0], $methode), $params);
    } else {
        //Sans
        $_load->$path[0]->$methode();
    }
}


/* ===== Fonction ===== */

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
