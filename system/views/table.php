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
            <span class="btn-label warning">Boutons d'actions</span><br>
            <div class="btn-group">
                <button class="btn btn-warning btn_action" data-action="update" title="modifier"><i class="material-icons">description</i></button>
                <button class="btn btn-warning btn_action" data-action="delete" title="supprimer"><i class="material-icons">delete_forever</i></button>
            </div>
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
                                $col = '<div style="position: relative;">' . $col . '<i class="material-icons" style="position: absolute; top:-10px;">vpn_key</i></div>';
                            }
                            echo '<th class="sort_table">' . $col . '</th>';
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
            <div class="pagination" style="position: absolute;top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <ul>
                    <li><span id="prev" class="btn btn-default disabled">«</span></li>
                    <?php
                    for ($i = 1; $i <= $pagine; $i++) {
                        $active = ($i === 1) ? 'active' : '';
                        echo '<li><span id="change_page_' . $i . '" class="btn btn-default ' . $active . ' change_page" data-num="' . $i . '">' . $i . '</span></li>';
                    }
                    ?>
                    <li><span id="next" class="btn btn-default">»</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        //Chargement des données
        updateTab();

        //Numero de la page
        var page = 1;
        //Precedent
        $('#prev').on('click', function () {
            //On retire la class active
            $('#change_page_' + page).removeClass('active');
            //Si on est pas sur la 1er page
            if (page > 1) {
                page--;
                //Si on arrive sur la 1er page on disabled le btn
                if (page == 1) {
                    $(this).addClass('disabled');
                }
                //On retire le disabled de next si il en a un
                if ($('#next').hasClass('disabled')) {
                    $('#next').removeClass('disabled');
                }
                //On change la class active pour le bon numero
                $('#change_page_' + page).addClass('active');
                //On appelle le script pour chercher les nouveau contenue a afficher
                updateTab(page, $('#limit').val());
            }
        });
        //Suivant
        $('#next').on('click', function () {
            //On retire la class active
            $('#change_page_' + page).removeClass('active');
            //Si on est pas sur la <?= $pagine ?>éme page
            if (page < <?= $pagine ?>) {
                page++;
                //Si on arrive sur la <?= $pagine ?>éme page on disabled le btn
                if (page == <?= $pagine ?>) {
                    $(this).addClass('disabled');
                }
                //On retire le disabled de next si il en a un
                if ($('#prev').hasClass('disabled')) {
                    $('#prev').removeClass('disabled');
                }
                //On change la class active pour le bon numero
                $('#change_page_' + page).addClass('active');
                //On appelle le script pour chercher les nouveau contenue a afficher
                updateTab(page, $('#limit').val());
            }
        });
        //Change de numero
        $('.change_page').on('click', function () {
            page = $(this).attr('data-num');
            //On retire la class active
            $('.change_page').each(function () {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
            //On retire ou ajoute les disabled sur prev et next selon les besoins
            if (page == 1) {
                $('#prev').addClass('disabled');
            } else if ($('#prev').hasClass('disabled')) {
                $('#prev').removeClass('disabled');
            }
            if (page == <?= $pagine ?>) {
                $('#next').addClass('disabled');
            } else if ($('#next').hasClass('disabled')) {
                $('#next').removeClass('disabled');
            }
            //On le met sur le nouveau
            $(this).addClass('active');
            //On appelle le script pour chercher les nouveau contenue a afficher
            updateTab(page, $('#limit').val());
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
            if (action == 'update') {
                //Mise à jour des lignes concerner

            } else if (action == 'delete') {
                //Suppression desl igne slectionnée
                $.post('<?= $_config['web_root'] ?>Modification/ajx_delete', params, function (data) {
                    console.log(data);
                });
            } else {
                //Autre => Erreur

            }
        });

    });

    function updateTab(page = 1, limit = <?= $limit ?>, order = '') {
        var params = [];
        params.push({'name': 'table', 'value': '<?= $nom ?>'});
        params.push({'name': 'page', 'value': page});
        params.push({'name': 'limit', 'value': limit});
        $('.pk').each(function () {
            params.push({'name': $(this).attr('name'), 'value': $(this).val()});
        });
        if (order.trim() === '') {
            $.post('<?= $_config['web_root'] ?>Affichage/ajx_tableContent', params, function (data) {
                $('#table_content').html(data);
            });
        } else {
            return false;
    }
    }
</script>