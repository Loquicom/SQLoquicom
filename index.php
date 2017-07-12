<?php

//Ini pour initialiser les fonctionnalités necessaire
require_once '_ini.php';

//Loader
require_once 'system/load.php';
$_load = Loader::get_loader();


//Si on reçoit des parametres en POST on les traites
if (isset($_POST['host']) && isset($_POST['name']) && isset($_POST['usr']) && isset($_POST['pass'])) {
    $_S['db'] = $_POST;
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
    }
}

//Si le script actuel n'est pas le script appelé on route, sinon on affiche la page de base d'affichage
$scriptAppeler = $_SERVER['REQUEST_URI'] . ((explode('/', $_SERVER['REQUEST_URI'])[count(explode('/', $_SERVER['REQUEST_URI'])) - 1] != 'index')?'index.php':'');
if ($scriptAppeler == $_SERVER['SCRIPT_NAME']) {
    $_load->load_controller('Affichage');
    $_load->affichage->index();
} else {
    var_dump($_SERVER);
}