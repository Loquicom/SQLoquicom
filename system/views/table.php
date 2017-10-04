<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_config;
?>

<div class="container">
    <div class="row-fluid">
        <div class="col8">
            <h1><?= $nom ?> <i id="show_info" class="material-icons pointer" title="Information sur la table">add_circle</i></h1>
        </div>
        <div class="col4">
            <div class="form-group">
                <label for="limit">Nombre d'élément par page</label>
                <input type="number" class="form-element" id="limit" value="<?= $limit ?>" min="1">
            </div>
        </div>
    </div>
    <div class="row-fluid hide" id="info" style="padding-bottom: 5em;">
        <div class="col12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Champ</th>
                        <th>Type</th>
                        <th>Null</th>
                        <th>Clef</th>
                        <th>Defaut</th>
                        <th>Extra</th>
                    </tr>
                </thead>
                <tbody id="table_info">
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class="col12">
            <div id="zone_message"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="col8">
            <span class="btn-label main-color text-color">Boutons d'actions</span><br>
            <div class="btn-group">
                <button class="btn btn-default btn_action main-color text-color" data-action="insert" title="insérer"><i class="material-icons">playlist_add</i></button>
                <button class="btn btn-default btn_action main-color text-color" data-action="update" title="modifier"><i class="material-icons">description</i></button>
                <button class="btn btn-default btn_action main-color text-color" data-action="delete" title="supprimer"><i class="material-icons">delete_forever</i></button>
                <button class="btn btn-default btn_action main-color text-color" data-action="truncate" title="vider"><i class="material-icons">restore_page</i></button>
            </div>
        </div>
        <div class="col4" style="text-align: right;">
            <span class="btn-label main-color text-color">Recherche</span><br>
            <input type="text" id="search" class="form-element">
        </div>
    </div>
    <div class="row-fluid">
        <div class="col12" style="overflow-x: auto; width: 100%">
            <table class="table">
                <thead>
                    <tr>
                        <?php
                        if (!empty($pk)) {
                            echo '<th class="center">Actions</th>';
                        }
                        foreach ($column as $col) {
                            //Si c'est une clef primaire
                            if (in_array($col, $pk)) {
                                echo '<th class="sort_table pointer" data-col="' . $col . '"><div style="position: relative;">' . $col . '<i class="material-icons" style="position: absolute; top:-10px;">vpn_key</i></div></th>';
                            } else {
                                echo '<th class="sort_table pointer" data-col="' . $col . '">' . $col . '</th>';
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody id="table_content">
                </tbody>
            </table>
            <!-- Clef primaire -->
            <?php
            if (!empty($pk)) {
                foreach ($pk as $clef) {
                    echo '<input class="pk" type="hidden" name="pk[]" value="' . $clef . '">';
                }
            }
            ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="col12" style="position: relative; margin-top: 2em; margin-bottom: 2em;">
            <div id="pagine"></div>
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
        <div class="offset8 col4">
            <span style="padding-right: 1em;"><button class="btn btn-default close-dialog">Annuler</button></span>
            <button id="conf_btn" class="btn btn-primary">Valider</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //L'order by et la page
    var order = '1';
    var pageActuel = '1';

    $(document).ready(function () {

        //Chargement des données
        updateTab();
        //Pagination
        pagine(<?= $pagine; ?>,
                'pagine',
                function (page) {
                    pageActuel = page;
                    updateTab(page, $('#search').val());
                });

        //Modifie le trie dans le tableau
        $('.sort_table').on('click', function () {
            var col = $(this).attr('data-col');
            //Si on est deja sur cette colonne on change l'ordre
            if (order == col) {
                order = col + ' desc';
            } else {
                order = col;
            }
            updateTab(pageActuel, $('#search').val());
        });

        //Recherche
        $('#search').on('change', function () {
            if($(this).val().trim() != ''){
                $('#pagine').hide();
            } else {
                $('#pagine').show();
            }
            updateTab(pageActuel, $(this).val());
        });

        //Si la limite change
        $('#limit').on('change', function () {
            //Mise a jour du contenue en updatant la page
            if ($(this).val() > 0) {
                location.href = '<?= $_config['web_root'] ?>Affichage/table/<?= $nom ?>/' + Math.round($(this).val());
            }
        });

        //Charge le tableau d'info
        $.post('<?= $_config['web_root'] ?>Affichage/ajx_tableInfo', {'table': '<?= $nom ?>'}, function (data) {
            $('#table_info').html(data);
        });

        //Afficher/Cacher tableau d'info
        $('#show_info').on('click', function () {
            if ($('#info').hasClass('hide')) {
                $('#info').slideDown('slow').removeClass('hide');
                $(this).html('remove_circle');
            } else {
                $('#info').slideUp('slow').addClass('hide');
                $(this).html('add_circle');
            }
        });

        //Action sur la table
        $('.btn_action').on('click', function () {
            var action = $(this).attr('data-action');
            var params = prepare_post('#table_content', {'table': '<?= $nom ?>'});
            if (action == 'insert') {
                //Ajout de ligne
                var params = prepare_post('#table_content', {'table': '<?= $nom ?>'});
                $.redirectPost('<?= $_config['web_root'] ?>Modification/insert', params);
            } else if (action == 'update') {
                //Au moins une case est cochée
                if (params.length > 1) {
                    //Mise à jour des lignes concerner
                    var params = prepare_post('#table_content', {'table': '<?= $nom ?>'});
                    $.redirectPost('<?= $_config['web_root'] ?>Modification/update', params);
                }
            } else if (action == 'delete') {
                //Au moins une case est cochée
                if (params.length > 1) {
                    //Ouverture boite de dialogue pour confirmation
                    $('#conf_action_text').html('Voulez vous vraiment supprimer la sélection ?');
                    $('#conf_btn').addClass('btn_valid_suppr');
                    dialog($('#conf_action').html());
                }
            } else if (action == 'truncate') {
                //Ouverture boite de dialogue pour confirmation
                $('#conf_action_text').html('Voulez vous vraiment vider la table ?');
                $('#conf_btn').addClass('btn_valid_trunc');
                dialog($('#conf_action').html());
            } else {
                //Autre => Erreur
                $('#zone_message').addClass('alert a-is-warning').html('Erreur inconnue');
            }
        });

        //Suppr ligne
        $('#dialog').on('click', '.btn_valid_suppr', function () {
            //Suppression des lignes slectionnées
            var params = prepare_post('#table_content', {'table': '<?= $nom ?>'});
            $.post('<?= $_config['web_root'] ?>Modification/ajx_delete', params, function (data) {
                $('#zone_message').removeClass();
                if (data.etat == 'ok') {
                    $('#zone_message').addClass('alert a-is-success').html(data.message);
                    //Supprime les lignes avec les cases cochées
                    $('.line_action').each(function () {
                        if ($(this).prop('checked')) {
                            $(this).closest('tr').remove();
                        }
                    });
                } else if (data.etat == 'err') {
                    $('#zone_message').addClass('alert a-is-danger').html(data.message);
                } else {
                    $('#zone_message').addClass('alert a-is-warning').html('Erreur inconnue');
                }
                //Fermeture de la dialogue
                dialog();
                $('#conf_btn').removeClass('btn_valid_suppr');
            }, 'json');
        });

        //Suppr ligne
        $('#dialog').on('click', '.btn_valid_trunc', function () {
            //Suppression des lignes slectionnées
            var params = prepare_post('#table_content', {'table': '<?= $nom ?>'});
            $.post('<?= $_config['web_root'] ?>Modification/ajx_truncate', params, function (data) {
                $('#zone_message').removeClass();
                if (data.etat == 'ok') {
                    $('#zone_message').addClass('alert a-is-success').html(data.message);
                    //Supprime les lignes
                    $('.line_action').each(function () {
                        $(this).closest('tr').remove();
                    });
                } else if (data.etat == 'err') {
                    $('#zone_message').addClass('alert a-is-danger').html(data.message);
                } else {
                    $('#zone_message').addClass('alert a-is-warning').html('Erreur inconnue');
                }
                //Fermeture de la dialogue
                dialog();
                $('#conf_btn').removeClass('btn_valid_suppr');
            }, 'json');
        });

    });

    function updateTab(page = 1, search = '', limit = <?= $limit ?>) {
        var params = [];
        params.push({'name': 'table', 'value': '<?= $nom ?>'});
        params.push({'name': 'page', 'value': page});
        params.push({'name': 'search', 'value': search});
        params.push({'name': 'limit', 'value': limit});
        params.push({'name': 'order', 'value': order});
        $('.pk').each(function () {
            params.push({'name': $(this).attr('name'), 'value': $(this).val()});
        });
        $.post('<?= $_config['web_root'] ?>Affichage/ajx_tableContent', params, function (data) {
            $('#table_content').html(data);
        });
    }
</script>
