<?php

defined('FC_INI') or exit('Acces Denied');

if (!function_exists('setLocalPref')) {

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

}

if (!function_exists('saveDatabase')) {

    function saveDatabase($host, $name, $usr, $pass, $rewrite = false) {
        //Création du dossier de sauvegarde si il n'existe pas
        if (!file_exists('../data/')) {
            mkdir('../data/');
            //Création de l'htaccess
            $htaccess = fopen('../data/.htaccess', 'w');
            fwrite($htaccess, 'Deny from all');
            fclose($htaccess);
        }
        //Création du fichier si il n'existe pas deja ou qu'il existe et que le reécrit
        if (!file_exists('../data/' . $host . '(-)' . $name . '.dat') || (file_exists('../data/' . $host . '(-)' . $name . '.dat') && $rewrite)) {
            $data = fopen('../data/' . $host . '(-)' . $name . '.dat', 'w');
            $content = md5($host . '(-)' . $name) . "\r\n";
            $content .= $host . "\r\n";
            $content .= $name . "\r\n";
            $content .= $usr . "\r\n";
            $content .= $pass;
            $content = base64_encode($content);
            $content .= "\r\n" . md5($content);
            fwrite($data, $content);
            fclose($data);
            return true;
        }
        return false;
    }

}

if (!function_exists('saveDatabaseContent')) {

    function saveDatabaseContent($host, $name, $usr, $pass) {
        $content = md5($host . '(-)' . $name) . "\r\n";
        $content .= $host . "\r\n";
        $content .= $name . "\r\n";
        $content .= $usr . "\r\n";
        $content .= $pass;
        $content = base64_encode($content);
        return $content;
    }

}