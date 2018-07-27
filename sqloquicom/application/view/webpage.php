<?php
defined('FC_INI') or exit('Acces Denied');
$fc = get_instance();
//$fc->load->controller('Securite');
?>

<!DOCTYPE html>
<html>
    <head>
        <!--Import css-->
        <link type="text/css" rel="stylesheet" href="<?= assets_url('css/schemaUI.css') ?>"/>
        <link type="text/css" rel="stylesheet" href="<?= assets_url('css/animate.css') ?>"/>
        <link type="text/css" rel="stylesheet" href="<?= assets_url('css/style.css') ?>"/>
        <link type="text/css" rel="stylesheet" href="<?= assets_url('css/pref.css') ?>"/>
        <!--Import jQuery and js-->
        <script type="text/javascript" src="<?= assets_url('js/jquery.min.js') ?>"></script>
        <script type="text/javascript" src="<?= assets_url('js/schemaUI.js') ?>"></script>
        <script type="text/javascript" src="<?= assets_url('js/script.js') ?>"></script>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <!-- Icone -->
        <link rel="shortcut icon" href="<?= assets_url('img/favicon.ico') ?>" type="image/x-icon" >
        <title><?= ($fc->config->get('pref', 'title') !== false) ? $fc->config->get('pref', 'title') : 'SQLoquicom'; ?></title>

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
                        <a class="text-color" href="<?= base_url() ?>"><?= ($fc->config->get('pref', 'title') !== false) ? $fc->config->get('pref', 'title') : 'SQLoquicom'; ?></a>
                    </div>
                    <?php if ($fc->session->connect !== false) { ?>
                        <nav class="right-float mobile-hidden">
                            <a class="text-color btn-pill" href="<?= redirect_url('Affichage') ?>">Tables</a>
                            <a class="text-color btn-pill" href="<?= redirect_url('Requeteur') ?>">Requeteur SQL</a>
                            <a class="text-color btn-pill" href="<?= redirect_url('Parametre') ?>">Param&egrave;tres</a>
                            <a class="text-color btn-pill" href="<?= redirect_url('Connexion/deco') ?>">Déconnexion</a>
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
                                <li><a style="color: white;" href="<?= redirect_url('Affichage') ?>">Tables</a></li>
                                <li><a style="color: white;" href="<?= redirect_url('Requeteur') ?>">Requeteur SQL</a></li>
                                <li><a style="color: white;" href="<?= redirect_url('Parametre') ?>">Param&egrave;tres</a></li>
                                <li><a style="color: white;" href="<?= redirect_url('Connexion/deco') ?>">Déconnexion</a></li>
                            </ul>
                        </div>
                    <?php } else if(($fc->controller('Securite')->is_secure() === false) || ($fc->controller('Securite')->is_secure() && $fc->controller('Securite')->is_connect())) { ?>
                        <nav class="right-float mobile-hidden">
                            <a class="text-color btn-pill" href="<?= redirect_url('Parametre') ?>">Param&egrave;tres</a>
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
                                <li><a style="color: white;" href="<?= redirect_url('Parametre') ?>">Param&egrave;tres</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </header>

        <main class="container">
            <?= $body ?>
        </main>
        
        <footer class="center" style="color: #bdbdbd; margin-top: 1em;">
            <div class="row-fluid">
                <div class="col12">
                    <img src="<?= assets_url('img/SQLoquicom-logo.svg') ?>" alt="SQLoquicom logo" width="30" height="30">
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
