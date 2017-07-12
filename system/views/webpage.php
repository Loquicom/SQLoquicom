<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_db;
global $_config;
?>

<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import css-->
        <link type="text/css" rel="stylesheet" href="<?=$_config['web_root']?>system/frmw/schemaUI.css"/>
        <link type="text/css" rel="stylesheet" href="<?=$_config['web_root']?>system/frmw/animate.css"/>
        <link type="text/css" rel="stylesheet" href="<?=$_config['web_root']?>system/frmw/style.css"/>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- Ajouter titre, desc, ... depuis le fichier de config -->
    </head>

    <body>
        <style>
            .center{
                text-align: center;
            }
        </style>

        <!-- Menu -->
        <header class="nav">
            <div class="container">
                <div class="row-fluid">
                    <div class="brand">
                        <a href="#">SQLoquicom</a>
                    </div>
                    <?php if ($_db !== null && $_db !== false) { ?>
                        <nav class="right-float mobile-hidden">
                            <a href="<?=$_config['web_root']?>Affichage" class="btn-pill">Tables</a>
                            <a href="#" class="btn-pill">Requeteur SQL</a>
                            <a href="#" class="btn-pill">Param&egrave;tre</a>
                            <a href="<?=$_config['web_root']?>Connexion/deco" class="btn-pill">Déconnexion</a>
                        </nav>
                        <div class="nav-toogle right-float show-mobile">
                            <i class="material-icons btn-mobile-nav">menu</i>
                        </div>
                        <div id="mobile-nav" class="hide">
                            <ul class="center">
                                <li style="height: 10px;"></li>
                                <li class="right-float"><i class="material-icons btn-mobile-nav" id>close</i></li><br>
                                <li>Menu</li>
                                <li><hr></li>
                                <li>Tables</li>
                                <li>Requeteur SQL</li>
                                <li>Param&egrave;tre</li>
                                <li>Déconnexion</li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </header>

        <main>
            <?= $body ?>
        </main>

        <!--Import jQuery and js-->
        <script type="text/javascript" src="<?=$_config['web_root']?>system/frmw/jquery.min.js"></script>
        <script type="text/javascript" src="<?=$_config['web_root']?>system/frmw/schemaUI.js"></script>
        <script type="text/javascript" src="<?=$_config['web_root']?>system/frmw/script.js"></script>
    </body>
</html>
