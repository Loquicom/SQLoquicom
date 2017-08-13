<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_db;
global $_config;
global $_pref;
?>

<!DOCTYPE html>
<html>
    <head>
        <!--Import css-->
        <link type="text/css" rel="stylesheet" href="<?= $_config['web_root'] ?>system/frmw/schemaUI.css"/>
        <link type="text/css" rel="stylesheet" href="<?= $_config['web_root'] ?>system/frmw/animate.css"/>
        <link type="text/css" rel="stylesheet" href="<?= $_config['web_root'] ?>system/frmw/style.css"/>
        <link type="text/css" rel="stylesheet" href="<?= $_config['web_root'] ?>data/pref.css"/>
        <!--Import jQuery and js-->
        <script type="text/javascript" src="<?= $_config['web_root'] ?>system/frmw/jquery.min.js"></script>
        <script type="text/javascript" src="<?= $_config['web_root'] ?>system/frmw/schemaUI.js"></script>
        <script type="text/javascript" src="<?= $_config['web_root'] ?>system/frmw/script.js"></script>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <!-- Icone -->
        <link rel="shortcut icon" href="<?= $_config['web_root'] ?>system/frmw/favicon.ico" type="image/x-icon" >

        <!-- Ajouter titre, desc, ... depuis le fichier de config -->
    </head>

    <body>
        <style>
            .center{
                text-align: center;
            }
        </style>

        <!-- Menu -->
        <header class="nav main-color">
            <div class="container">
                <div class="row-fluid">
                    <div class="brand title">
                        <a class="text-color" href="<?= $_config['web_root'] ?>"><?= (isset($_pref['title'])) ? $_pref['title'] : 'SQLoquicom'; ?></a>
                    </div>
                    <?php if ($_db !== null && $_db !== false) { ?>
                        <nav class="right-float mobile-hidden">
                            <a class="text-color btn-pill" href="<?= $_config['web_root'] ?>Affichage">Tables</a>
                            <a class="text-color btn-pill" href="#">Requeteur SQL</a>
                            <a class="text-color btn-pill" href="<?= $_config['web_root'] ?>Parametre">Param&egrave;tre</a>
                            <a class="text-color btn-pill" href="<?= $_config['web_root'] ?>Connexion/deco">Déconnexion</a>
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

        <main class="container">
            <?= $body ?>
        </main>
        
        <footer class="center" style="color: #bdbdbd">
            <div class="row-fluid">
                <div class="col12">
                    <img src="<?= $_config['web_root'] ?>system/frmw/SQLoquicom-logo.svg" alt="SQLoquicom logo" width="30" height="30">
                </div>
            </div>
            <div class="row-fluid">
                <div class="col12">
                    SQLoquicom &COPY; <?= date('Y') ?> Loquicom [<a href="http://loquicom.fr" target="_blank" style="color: #bdbdbd">loquicom.fr</a>]
                </div>
            </div>       
        </footer>
        
        <div id="dialog" class="dialog_bck hide">
            <div id="dialog_content" class="dialog_content">
            </div>
        </div>

    </body>
</html>
