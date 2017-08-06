<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_pref;
global $_config;
?>

<style>
    .fab {
        position: relative;
        display: block;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        text-align: center;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        transition: all 0.4s ease-in-out;
        margin-top: 3.5em;
    }
    .fab:focus, .link:focus, .pick.focus{
        outline:0;
    }
    .fab:hover {
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        width: 100px;
        height: 100px;
        border-radius: 1px;
    }
    .link {
        position: absolute;
        width: 28px;
        height: 28px;
        right: 14px;
        bottom: 14px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        cursor:pointer;
        transition: all 0.4s ease-in-out;
    }
    .pick {
        display:block;
        position: absolute;
        text-align:center;
        width: 40px;
        height: 40px;
        right:9px;
        bottom:15px;
        color:#000;
        cursor:pointer;
        transition: all 0.4s ease-in-out;
    }
    .pick>i{
        color:#000;
        line-height:56px;
        font-size:2em;
    }
    .link:nth-child(1) {
        transform: scale(2) rotate(0deg);
    }
    .link:nth-child(2) {
        transform: scale(2) rotate(-23deg);
    }
    .link:nth-child(3) {
        transform: scale(2) rotate(-46deg);
    }
    .link:nth-child(4) {
        transform: scale(2) rotate(-69deg);
    }
    .link:nth-child(5) {
        transform: scale(2) rotate(-90deg);
    }
    .fab:hover .link {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s ease-in-out;
        border-radius: 1px;
    }
    .fab:hover .link:nth-child(1) {
        transform: scale(2) rotate(0deg) translateY(-50px);
        transition-delay: 0.4s;
    }
    .fab:hover .link:nth-child(2) {
        transform: scale(2) rotate(-24deg) translateY(-50px);
        transition-delay: 0.3s;
    }
    .fab:hover .link:nth-child(3) {
        transform: scale(2) rotate(-46deg) translateY(-50px);
        transition-delay: 0.2s;
    }
    .fab:hover .link:nth-child(4) {
        transform: scale(2) rotate(-68deg) translateY(-50px);
        transition-delay: 0.1s;
    }
    .fab:hover .link:nth-child(5) {
        transform: scale(2) rotate(-90deg) translateY(-50px);
    }
</style>

<div class="container">
    <div class="row">
        <div class="col12">
            <h1>Personnalisation</h1>
        </div>
    </div>
    <div id="form" style="margin-top: 3em;">
        <div class="row">
            <div class="col12">
                <span style="font-size: 1.5em; padding-left: 1em;"><i>Couleur du th&egrave;me :</i></span>
            </div>
        </div>
        <div class="row">
            <div class="col2">
                <div class="fab" data-preview="preview-theme" data-input="color-theme"  data-link="0">
                    <div class="link link_0"></div>
                    <div class="link link_0"></div>
                    <div class="link link_0"></div>
                    <div class="link link_0"></div>
                    <div class="link link_0"></div>
                    <div class="pick"><i class="material-icons">navigate_next</i></div>
                </div>
            </div>
            <div class="col10">
                <div class="form-element" id="preview-theme" style="height: 56px; margin-top: 3.5em; background-color: <?= $_pref['color'] ?>;"></div>
            </div>
            <input type="hidden" name="color-theme" id="color-theme" value="<?= $_pref['color']; ?>">
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col12">
                <span style="font-size: 1.5em; padding-left: 1em;"><i>Couleur du texte :</i></span>
            </div>
        </div>
        <div class="row">
            <div class="col2">
                <div class="fab" data-preview="preview-text" data-input="color-text" data-link="1">
                    <div class="link link_1"></div>
                    <div class="link link_1"></div>
                    <div class="link link_1"></div>
                    <div class="link link_1"></div>
                    <div class="link link_1"></div>
                    <div class="pick"><i class="material-icons" style="color: #424242;">navigate_next</i></div>
                </div>
            </div>
            <div class="col10">
                <div class="form-element" id="preview-text" style="height: 56px; margin-top: 3.5em; background-color: <?= $_pref['text'] ?>;"></div>
            </div>
            <input type="hidden" name="color-text" id="color-text" value="<?= $_pref['text']; ?>">
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col12">
                <span style="font-size: 1.5em; padding-left: 1em;"><i>Titre du site</i></span>
                <input class="form-element" type="text" name="title" id="title" value="<?= $_pref['title'] ?>">
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col12 center">
                <button type="button" id="btn_submit" class="btn btn-primary main-color text-color">Sauvegarder</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col12">
            <h1>Gestion des bases de donn&eacute;es sauvegard&eacute;es</h1>
        </div>
    </div>
    <div class="row" style="margin-bottom: 8em;">
        <div class="col12">
            <table class="table">
                <thead>
                    <tr>
                        <th>H&ocirc;te</th>
                        <th>Nom de la base</th>
                        <th>Nom du fichier</th>
                        <th class="center">Supprimer fichier</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <?php
                    if ($bd === null) {
                        echo '<tr><td colspan="4"><div class="alert a-is-info"><i class="material-icons">info</i>Aucune base de donn&eacute;es sauvegard&eacute;es</div></td></tr>';
                    } else {
                        foreach ($bd as $base) {
                            $hote = explode('_', $base)[0];
                            $nom = str_replace('.dat', '', explode('_', $base)[1]);
                            ?>
                            <tr class="line">
                                <td><?= $hote ?></td>
                                <td><?= $nom ?></td>
                                <td><?= $base ?></td>
                                <td class="center"><button class="btn btn-default btn_action main-color text-color suppr_file" data-file="<?= $base ?>" title="supprimer"><i class="material-icons">delete_forever</i></button></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contenue pour les dialog -->
<div id="conf_action" class="hide">
    <div class="row-fluid alert a-is-warning">
        <div class="col1">
            <i class="material-icons">warning</i>
        </div>
        <div class="col11" id="conf_action_text">
        </div>
    </div>
    <div class="row-fluid" style="padding-top: 1em; padding-bottom: 2em;">
        <div class="offset10 col2">
            <span style="padding-right: 1em;"><button class="btn btn-default close-dialog text-color">Annuler</button></span>
            <button id="conf_btn" class="btn btn-default main-color text-color">Valider</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //Color picker
    var r = [0, 4];
    $('.link').click(function (e) {
        var preview = $(this).closest('div.fab').attr('data-preview');
        var input = $(this).closest('div.fab').attr('data-input');
        var rgb = $(this).css("background").split(')')[0] + ')';
        console.log(rgb);
        $('#' + preview).css("background", $(this).css("background"));
        $('#' + input).val(rgb2hex(rgb));
    });

    $('.pick').click(function (e) {
        var j = 1;
        var link = $(this).closest('div.fab').attr('data-link');
        $(this).closest('div.fab').css("background", color[r[link]][0]);
        $('.link_' + link).each(function (f) {
            $(this).css("background", color[r[link]][j]);
            j++;
        });
        if (r[link] < color.length - 1)
            r[link]++;
        else
            r[link] = 0;
    });

    var color = [
        [
            "#ff6f00",
            "#ffecb3",
            "#ffd54f",
            "#ffc107",
            "#ffa000",
            "#ff8f00"
        ],
        [
            "#b71c1c",
            "#ffcdd2",
            "#ef9a9a",
            "#ef5350",
            "#e53935",
            "#c62828"
        ],
        [
            "#0d47a1",
            "#bbdefb",
            "#64b5f6",
            "#2196f3",
            "#1976d2",
            "#1565c0"
        ],
        [
            "#1b5e20",
            "#c8e6c9",
            "#81c784",
            "#4caf50",
            "#388e3c",
            "#2e7d32"
        ],
        [
            "#000000",
            "#ffffff",
            "#bdbdbd",
            "#757575",
            "#424242",
            "#000000"
        ]
    ];

    //Js page
    $(document).ready(function () {
        //Active les color pickers
        $('.pick').click();

        //Envoie formulaire
        $('#btn_submit').on('click', function () {
            var params = prepare_post('#form');
            $.post('<?= $_config['web_root'] ?>Parametre/ajx_preference', params, function (data) {
                location.reload();
            });
        });

        //Suppression fichier
        var line = null;
        var file = null;
        $('.suppr_file').on('click', function () {
            line = $(this).closest('tr');
            file = $(this).attr('data-file');
            $('#conf_action_text').html('Voulez vous vraiment supprimer la sélection ?');
            $('#conf_btn').addClass('btn_valid_suppr');
            dialog($('#conf_action').html());

        });
        $('#dialog').on('click', '.btn_valid_suppr', function () {
            $.post('<?= $_config['web_root'] ?>Parametre/ajx_supprFile', {'file': file}, function (data) {
                if (data.etat == 'ok') {
                    line.remove();
                    if ($('.line').length < 1) {
                        $('#tbody').html('<tr><td colspan="4"><div class="alert a-is-info"><i class="material-icons">info</i>Aucune base de donn&eacute;es sauvegard&eacute;es</div></td></tr>');
                    }
                }
                dialog();
            }, 'json');
        });

    });

    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
        return (rgb && rgb.length === 4) ? "#" +
                ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
    }

</script>