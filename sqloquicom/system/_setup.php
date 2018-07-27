<?php

/* ==============================================================================
  Fraquicom [PHP Framework] by Loquicom <contact@loquicom.fr>

  GPL-3.0
  _setup.php
  ============================================================================ */

//Simulation des donnéesdu fichier ini
$data['mode']['mvc'] = 'on';
$data['route']['routage_asset'] = 'on';
$data['root']['fileroot'] = '';
$data['root']['webroot'] = '';

//Création de l'htacces de routage en fonction de l'ini
$htaccess = fopen('../.htaccess', 'w');
if($htaccess === false){
    //Si le fichier n'est pas correctement ouvert
    exit("Impossible de créer les fichiers de configuration");
}
$code = 'Options +FollowSymLinks' . "\r\n\r\n";
$code .= 'RewriteEngine On' . "\r\n\r\n";
$code .= 'RewriteBase /' . "\r\n\r\n";
$code .= 'RewriteCond $1 !^(sqloquicom/index\.php|sqloquicom/robots\.txt' . (($data['route']['routage_asset'] == 'on') ? '' : '|sqloquicom/assets') . ')' . "\r\n\r\n";
$code .= 'RewriteRule ^(.*)$ ' . substr($_SERVER['SCRIPT_NAME'], 1) . '?_fc_r=$1 [L]';
fwrite($htaccess, $code);
fclose($htaccess);

//Création du fichir de config local
if (!file_exists('./system/config/')) {
    @mkdir('./system/config');
}
$root = ($_SERVER['REQUEST_URI'] == '/') ? './' : $_SERVER['REQUEST_URI'];
$webroot = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$local = fopen('./system/config/local.php', 'w');
$code = '<?php' . "\r\n\r\n";
$code .= '$_config[\'root\'] = "' . ((trim($data['root']['fileroot']) != '') ? $data['root']['fileroot'] : $root) . '";' . "\r\n";
$code .= '$_config[\'routage_asset\'] = ' . (($data['route']['routage_asset'] == 'on') ? 'true' : 'false') . ';' . "\r\n";
$code .= '$_config[\'web_root\'] = "' . str_replace('sqloquicom/index.php', '', ((trim($data['root']['webroot']) != '') ? $data['root']['webroot'] : $webroot)) . '";' . "\r\n";
$code .= '$_config[\'mode\'] = "' . (($data['mode']['mvc'] == 'on') ? 'mvc' : 'no_mvc') . '";' . "\r\n";
$code .= '$_config[\'md5\'] = "' . md5_file('./fraquicom.ini') . '";' . "\r\n";
fwrite($local, $code);
fclose($local);

//Creation fichier preference locale
setLocalPref();

//Redirection sur page principal
header('Location: ../');
exit;

/* ===== Fonction ===== */

function copy_dir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * Vide un dossier
 * @author Loquicom
 * @param string $folderPath - Le chemin du fichier
 * @param boolean $subfolder - Supprimer aussi les sous dossier
 * @param boolean $delete - Supprimer le dossier courant
 */
function clear_folder($folderPath, $subfolder = false, $delete = false) {
    //On verifie que c'est un fichier
    if (is_dir($folderPath)) {
        //On ajoute un slash a lafin si il n'y en a pas
        if ($folderPath[strlen($folderPath) - 1] != '/') {
            $folderPath .= '/';
        }
        //Recup tous les fichiers
        $files = array_diff(scandir($folderPath), array('..', '.'));
        //Parcours des fichiers
        foreach ($files as $file) {
            //Si ce sont des fichiers
            if (is_file($folderPath . $file)) {
                unlink($folderPath . $file);
            }
            //Sinon ce sont des dossier et supprime seulement si subFolder = true
            else if ($subfolder) {
                //On rapelle cette fontion pour vider le dossier
                clear_folder($folderPath . $file, true, true);
            }
        }
        //Si $delete on supprime aussi le fichier actuel
        if ($delete) {
            @rmdir($folderPath);
        }
    }
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
        if (file_exists('assets/css/pref.css') && file_exists('application/config/pref.php')) {
            $exec = false;
        }
    }
    if ($exec) {
        /* --- CSS zvec la couleur principal --- */
        //Creation du fichier
        $prefCss = fopen('assets/css/pref.css', 'w');
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
        $pref = fopen('application/config/pref.php', 'w');
        $php = '<?php' . "\r\n";
        $php .= '$config[\'pref\'][\'title\'] = "' . $title . '";' . "\r\n";
        $php .= '$config[\'pref\'][\'color\'] = "' . $color . '";' . "\r\n";
        $php .= '$config[\'pref\'][\'text\'] = "' . $text . '";' . "\r\n";
        fwrite($pref, $php);
        fclose($pref);
        return true;
    }
    return false;
}
