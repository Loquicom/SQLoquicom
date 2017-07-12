<?php

/**
 * Fichier uniquement pour eviter l'accès à la liste des fichiers sur le serveur
 * Redirige sur le vrai index.php du serveur
 */

//Recup le nom du script courant
$path_script = explode('/', $_SERVER['SCRIPT_NAME']);
if (count($path_script) == 1) {
    //Si qu'une partie on essaye avec des antislash (Windows)
    $path_script = explode('\\', $_SERVER['SCRIPT_NAME']);
}
//On recup la derniere partie du tableau
$script_name = $path_script[count($path_script) - 1];
//On regarde le dossier qui est avant le script
$dir_name = $path_script[count($path_script) - 2];
//Si le fichier est différent de index.php on redirige
if ($script_name != 'index.php') {
    //Si c'est system le dernier dossier
    if ($dir_name == 'system') {
        header('Location: ../');
    }
    //Sinon c'est ue c'est un des sous dossier de system
    else {
        header('Location: ../../');
    }
}
//On verifie qu'il sont sur le bon index
else {
    //Si l'un de ces dossier est le dossier parent on n'est pas dans le bon index.php on redirige en conséquence
    if (in_array($dir_name, array('system', 'models', 'views', 'controllers'))) {
        if ($dir_name == 'system') {
            header('Location: ../');
        } else {
            header('Location: ../../');
        }
    }
}