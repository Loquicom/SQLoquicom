<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_config;
?>

<div class="container">
    <div class="row-fluid">
        <div class="col12">
            <a href="<?= $_config['web_root'] ?>Affichage/table/<?= $table ?>" class="btn btn-warning inline" title="Retour" style="margin-right: 1em;"><i class="material-icons">arrow_back</i></a>
            <h1 class="inline">Update <?= $table ?></h1>
        </div>
    </div>
</div>