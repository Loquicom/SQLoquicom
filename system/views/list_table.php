<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
?>

<style>
    .center{
        text-align: center;
    }
</style>

<div class="container">
    <div class="col2"></div>
    <div class="col8">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom de la table</th>
                    <th>Nombre de ligne</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tables as $name => $nbLigne) {
                    ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td><?= $nbLigne ?></td>
                        <td>Todo</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col2"></div>
</div>