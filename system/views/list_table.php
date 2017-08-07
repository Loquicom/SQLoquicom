<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_config;
?>

<div class="container">
    <div class="row-fluid">
        <div class="col2"></div>
        <div class="col8">
            <table class="table" style="margin-bottom: 3em;">
                <thead>
                    <tr>
                        <th>Nom de la table</th>
                        <th class="mobile-hidden" style="text-align: center;">Nombre de ligne</th>
                        <th style="padding-left: 2.8em;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($tables as $name => $nbLigne) {
                        ?>
                        <tr>
                            <td><?= $name ?></td>
                            <td class="mobile-hidden center"><?= $nbLigne ?></td>
                            <td data-name="<?= $name ?>">
                                <div class="btn-group">
                                    <button class="btn btn-default btn_view_table main-color text-color" title="Contenue de la table"><i class="material-icons">view_list</i></button>
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
        <div class="col2"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.btn_view_table').on('click', function () {
            var table = $(this).closest('td').attr('data-name');
            location.href = '<?= $_config['web_root'] ?>Affichage/table/' + table;
        });
    });
</script>