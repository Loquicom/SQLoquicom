<?php

//Creation constante
define('APPLICATION', 'LOQUICOM_FRAMEWORK');

//Création des variables globals
global $_config;
global $_db;
global $_err;
global $_load;


//Demarre la session si besoin
if (trim(session_id()) === '') {
    session_start();
    //Création d'un raccourci pour la session
    $_S = & $_SESSION;
}

//Fichier de config
require_once 'system/config.php';

//Class mere des controllers et models
require_once 'system/controllers/ControllerIni.php';

//Adaptation du niveau d'erreur
if ($_config['mode'] == 'dev') {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    //ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
} else if ($_config['mode'] == 'test') {
    error_reporting(E_ERROR);
    //ini_set('error_reporting', E_ERROR);
} else if ($_config['mode'] == 'prod') {
    error_reporting(0);
    //ini_set('error_reporting', 0);
}

