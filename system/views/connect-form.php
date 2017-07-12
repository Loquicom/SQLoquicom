<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
?>

<!--form name="connect" method="POST">
    <input type="text" name="host">
    <input type="text" name="name">
    <input type="text" name="usr">
    <input type="password" name="pass">
    <button type="submit">Connexion</button>
</form-->

<div class="container">
    <div class="col2"></div>
    <div class="col8">
        <form name="connect" method="POST">
            <div class="form-group">
                <label for="hote">Nom de l'hote</label>
                <input type="text" name="host" class="form-element" id="hote" placeholder="localhost">
            </div>
            <div class="form-group">
                <label for="base">Nom de la base</label>
                <input type="text" name="name" class="form-element" id="base">
            </div>
            <div class="form-group">
                <label for="user">Login</label>
                <input type="text" name="usr" class="form-element" id="user" placeholder="root">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" name="pass" class="form-element" id="mdp">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="keep" style="margin-right: 1em;">Garder la base en mémoire
                </label>
            </div>
            <div class="center">
                <button type="submit" class="btn btn-primary">Connexion</button>
            </div>
        </form>
    </div>
</div>