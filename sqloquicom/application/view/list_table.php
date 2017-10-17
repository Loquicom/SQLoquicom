<?php
defined('FC_INI') or exit('Acces Denied');
?>


<div class="row-fluid">
    <div class="offset1 col10">
        <span class="btn-label main-color text-color">Ajouter une table</span><br>  
        <a href="<?= redirect_url('Structure/create') ?>" class="btn btn-default btn_action main-color text-color" title="créer" style="margin-left: 1.5em;"><i class="material-icons">add</i></a>
    </div>
</div>

<div class="row-fluid">
    <div class="offset1 col10">
        <table class="table" style="margin-bottom: 3em;">
            <thead>
                <tr>
                    <th>Nom de la table</th>
                    <th class="mobile-hidden" style="text-align: center;">Nombre de ligne</th>
                    <th style="padding-left: 4.5em">Action</th><!-- 4.5em quand btn alter actif sinon 2.6 -->
                </tr>
            </thead>
            <tbody id="tbody">
                <?php
                if (count($tables) <= 0) {
                    echo '<tr><td colspan="3"><div class="alert a-is-info"><i class="material-icons">info</i>Aucune table</div></td></tr>';
                }
                foreach ($tables as $name => $nbLigne) {
                    ?>
                    <tr class="line">
                        <td><?= $name ?></td>
                        <td class="mobile-hidden center"><?= $nbLigne ?></td>
                        <td data-name="<?= $name ?>">
                            <div class="btn-group">
                                <button class="btn btn-default btn_view_table main-color text-color" title="Contenue de la table"><i class="material-icons">view_list</i></button>
                                <button class="btn btn-default btn_alter_table main-color text-color" title="Modifier la table"><i class="material-icons">edit</i></button>
                                <button class="btn btn-default brn_suppr_table main-color text-color" title="Supprimer la table"><i class="material-icons">delete_forever</i></button>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
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
        <div class="offset8 co4">
            <span style="padding-right: 1em;"><button class="btn btn-default close-dialog">Annuler</button></span>
            <button id="conf_btn" class="btn btn-default main-color text-color">Valider</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //Affichage
        $('.btn_view_table').on('click', function () {
            var table = $(this).closest('td').attr('data-name');
            location.href = '<?= redirect_url('Affichage/table/') ?>' + table;
        });

        //Alter
        $('.btn_alter_table').on('click', function () {
            var table = $(this).closest('td').attr('data-name');
            location.href = '<?= redirect_url('Structure/alter/') ?>' + table;
        });

        //Suppression fichier
        var line = null;
        var table = null;
        $('.brn_suppr_table').on('click', function () {
            line = $(this).closest('tr');
            table = $(this).closest('td').attr('data-name');
            $('#conf_action_text').html('Voulez vous vraiment supprimer la table ?');
            $('#conf_btn').addClass('btn_valid_suppr');
            dialog($('#conf_action').html());

        });
        $('#dialog').on('click', '.btn_valid_suppr', function () {
            $.post('<?= redirect_url('Structure/ajx_drop') ?>', {'table': table}, function (data) {
                if (data.etat == 'ok') {
                    line.remove();
                    if ($('.line').length < 1) {
                        $('#tbody').html('<tr><td colspan="3"><div class="alert a-is-info"><i class="material-icons">info</i>Aucune table</div></td></tr>');
                    }
                }
                dialog();
            }, 'json');
        });
    });
</script>