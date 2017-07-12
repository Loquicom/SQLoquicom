<?php
(defined('APPLICATION'))?'':exit('Acces denied');
?>

<form name="connect" method="POST">
    <input type="text" name="host">
    <input type="text" name="name">
    <input type="text" name="usr">
    <input type="password" name="pass">
    <button type="submit">Connexion</button>
</form>