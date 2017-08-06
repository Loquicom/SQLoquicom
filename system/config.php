<?php
(defined('APPLICATION'))?'':exit('Acces denied');


//Initialisation de la variable de config

/**
 * Definit le mode du site
 * dev -> affiche toutes les erreur
 * test -> affiche uniquement les erreur fatale
 * prod -> cache tout
 * tous autre laisse par defaut
 */
$_config['mode'] = 'dev';

/* Decommenter pour forcer les valeurs dans ce fichier
$_config['root'] = '/SQLoquicom/';


$_config['web_root'] = 'http://localhost/SQLoquicom/';
//*/