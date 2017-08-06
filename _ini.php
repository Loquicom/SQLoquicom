<?php

//Creation constante
define('APPLICATION', 'LOQUICOM_FRAMEWORK');

//Création des variables globals
global $_config;
global $_db;
global $_err;
global $_load;
global $_pref;


//Demarre la session si besoin
if (trim(session_id()) === '') {
    session_start();
    //Création d'un raccourci pour la session
    $_S = & $_SESSION;
}

//Parametre le config local et l'htacces
setLocalConfig();
//Chargement
require_once 'data/local_config.php';

//Fichier de config
require_once 'system/config.php';

//Class mere des controllers et models
require_once 'system/controllers/ControllerIni.php';
require_once 'system/models/ModelIni.php';

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

//Création des fichiers avec les preferences
setLocalPref();
//Chargemenr
@include 'data/pref.php';

/* ===== Fonction ===== */

function setLocalConfig() {
    $modif = false;
    //Si le fichier local_config n'existe pas
    if (!file_exists('data/local_config.php')) {
        //On créer le dossier si besoin
        if (!file_exists('data/')) {
            mkdir('./data/');
            $index = fopen('data/index.html', 'w');
            fwrite($index, '<h1>Forbidden</h1>');
            fclose($index);
        }
        //Création du fichier de config
        $localConfig = fopen('data/local_config.php', 'w');
        //Ecriture des variables locals dans le fichier
        $code = '<?php' . "\r\n";
        $code .= '$_config[\'root\'] = "' . (($_SERVER['REQUEST_URI'] == '/') ? './' : $_SERVER['REQUEST_URI']) . '";' . "\r\n";
        $code .= '$_config[\'web_root\'] = "' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '";' . "\r\n";
        fwrite($localConfig, $code);
        fclose($localConfig);
        //On indique que l'on modifié config
        $modif = true;
    }
    //Si le fichier .htaccess n'existe pas ou si config a été modifié
    if (!file_exists('./.htaccess') || $modif) {
        //Ecriture de l'htacces
        $htaccess = fopen('./.htaccess', 'w');
        $code = 'Options +FollowSymLinks' . "\r\n\r\n";
        $code .= 'RewriteEngine On' . "\r\n\r\n";
        $code .= 'RewriteBase /' . "\r\n\r\n";
        $code .= 'RewriteCond $1 !^(index\.php|robots\.txt|system/frmw|data)' . "\r\n\r\n";
        $code .= 'RewriteRule ^(.*)$ ' . substr($_SERVER['SCRIPT_NAME'], 1) . '?r=$1 [L]';
        fwrite($htaccess, $code);
        fclose($htaccess);
        return true;
    }
    return false;
}

function setLocalPref($color = '#ffc107', $text = '#000000', $title = 'SQLoquicom', $ifNotExist = true) {
    //Si les variables sont vide on met leur valeur par default
    if (!(is_string($color) && trim($color) != '')) {
        $color = '#ffc107';
    }
    if (!(is_string($text) && trim($text) != '')) {
        $text = '#000000';
    }
    if (!(is_string($title) && trim($title) != '')) {
        $title = 'SQLoquicom';
    }
    //Variable indiquant si on execute ou pas
    $exec = true;
    if ($ifNotExist) {
        //Si on execute uniquement quand il n'existz pas on verifier que les deux fichier sont créer pour desactivier l'execution
        if (file_exists('data/pref.css') && file_exists('data/pref.php')) {
            $exec = false;
        }
    }
    if ($exec) {
        /* --- CSS zvec la couleur principal --- */
        //On créer le dossier si besoin
        if (!file_exists('data/')) {
            mkdir('./data/');
            $index = fopen('data/index.html', 'w');
            fwrite($index, '<h1>Forbidden</h1>');
            fclose($index);
        }
        //Creation du fichier
        $prefCss = fopen('data/pref.css', 'w');
        //CSS
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $css = '.main-color{background-color: ' . $color . ' !important;}' . "\r\n";
        $css .= '.text-color{color: ' . $text . ' !important;}' . "\r\n";
        $css .= '.btn.main-color:hover{opacity: .8 !important;}' . "\r\n";
        $css .= 'li > .active {' . "\r\n";
        $css .= '   background-color: ' . $color . ' !important;' . "\r\n";
        $css .= '   color: ' . $text . ' !important;' . "\r\n";
        $css .= '   border-color: ' . $color . ' !important;' . "\r\n";
        $css .= '}' . "\r\n";
        $css .= '.form-element:focus{' . "\r\n";
        $css .= '   border-color: ' . $color . ' !important;' . "\r\n";
        $css .= '   box-shadow: 0 0 8px rgba(' . $r . ', ' . $g . ', ' . $b . ', 0.6) !important;' . "\r\n";
        $css .= '}' . "\r\n";
        $css .= 'nav>a:hover{' . "\r\n";
        $css .= '   color: ' . $color . ' !important;' . "\r\n";
        $css .= '}' . "\r\n";
        $css .= '.title>a:hover{' . "\r\n";
        $css .= '   color: ' . $text . ' !important;' . "\r\n";
        $css .= '}' . "\r\n";
        fwrite($prefCss, $css);
        fclose($prefCss);
        /* --- Preference d'affichage --- */
        $pref = fopen('data/pref.php', 'w');
        $php = '<?php' . "\r\n";
        $php .= '$_pref[\'title\'] = "' . $title . '";' . "\r\n";
        $php .= '$_pref[\'color\'] = "' . $color . '";' . "\r\n";
        $php .= '$_pref[\'text\'] = "' . $text . '";' . "\r\n";
        fwrite($pref, $php);
        fclose($pref);
        return true;
    }
    return false;
}
