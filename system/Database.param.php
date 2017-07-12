<?php
(defined('APPLICATION'))?'':exit('Acces denied');

//Appelle la class de connexion à la BD
require_once 'Database.class.php';

//False dans DB pour indiquer que pas connecté
$_db = false;

//Parametre la connexion si les arguments sont connus
if (isset($_S['db']['host']) && trim($_S['db']['host']) != '' && isset($_S['db']['name']) && trim($_S['db']['name']) != '' && isset($_S['db']['usr']) && trim($_S['db']['usr']) != '' && isset($_S['db']['pass'])) {
    try {
        Database::setConfiguration('mysql:host=' . $_S['db']['host'] . ';dbname=' . $_S['db']['name'] . ';charset=utf8', $_S['db']['usr'], $_S['db']['pass']);
        $_db = Database::getInstance();
    } catch (Exception $e) {
        $_err = ($e->getMessage());
    }
}
