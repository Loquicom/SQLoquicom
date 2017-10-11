<?php
defined('FC_INI') or exit('Acces Denied');
?>

<div class="container">
    <div class="row-fluid">
        <div class="col8">
            <a href="<?= redirect_url('Affichage/table/' . $table) ?>" class="btn btn-default main-color text-color inline" title="Retour" style="margin-right: 1em;"><i class="material-icons">arrow_back</i></a>
            <h1 class="inline">Insert <?= $table ?></h1>
        </div>
        <div class="col4" style="text-align: right;">
            <button type="button" class="btn btn-default main-color text-color inline add_line" title="Ajouter une ligne" style="margin-right: 1em;"><i class="material-icons">add</i></button>
            <button type="button" class="btn btn-default main-color text-color inline valid_insert" title="Valider"><i class="material-icons">check</i></button>
        </div>
    </div>
    <div id="insert_form">
        <input type="hidden" name="table" value="<?= $table ?>">
        <span class="btn-label main-color text-color">Ligne 1</span><br>
        <div class="row-fluid">
            <?php 
            $i = 0;
            foreach ($col as $nom) { 
                ?>
                <div class="col3 form-group">
                    <label for="hote" style="position: relative;"><?= $nom ?> <?= ($infos[$i]['Null'] == 'YES')?'[Null]':'' ?> <?= ($infos[$i]['Key'] == 'PRI')?'<i class="material-icons" style="position: absolute;">vpn_key</i>':'' ?></label>
                    <input type="text" name="<?= $nom ?>[0]" class="form-element" placeholder="<?= $infos[$i]['Type'] ?>">
                </div>
                <?php 
                $i++;
            } 
            ?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="col12" style="text-align: right;">
            <button type="button" class="btn btn-default main-color text-color inline add_line" title="Ajouter une ligne" style="margin-right: 1em;"><i class="material-icons">add</i></button>
            <button type="button" class="btn btn-default main-color text-color inline valid_insert" title="Valider"><i class="material-icons">check</i></button>
        </div>
    </div>
    <div class="row-fluid alert a-is-info hide" id="return_zone" style="margin-top: 3em;">
        <i class="material-icons">info</i>
        <div class="col11" id="return_text">
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var line = 1;
    
    $('.add_line').on('click', function(){
        var html = '<span class="btn-label main-color text-color">Ligne ' + (line + 1) + '</span><br>' + "\n";
        html += '<div class="row-fluid">' + "\n";
        <?php 
        $i = 0;
        foreach ($col as $nom) { ?>
            html += '   <div class="col3 form-group">' + "\n";
            html += '       <label for="hote" style="position: relative;"><?= $nom ?> <?= ($infos[$i]['Null'] == 'YES')?'[Null]':'' ?> <?= ($infos[$i]['Key'] == 'PRI')?'<i class="material-icons" style="position: absolute;">vpn_key</i>':'' ?></label>' + "\n";
            html += '       <input type="text" name="<?= $nom ?>[' + line + ']" class="form-element" placeholder="<?= $infos[$i]['Type'] ?>">' + "\n";
            html += '   </div>' + "\n";
        <?php 
        $i++;
        } 
        ?>
        html += '</div>' + "\n";
        line++;
        $('#insert_form').append(html);
    });
    
    $('.valid_insert').on('click', function(){
        var params = prepare_post('#insert_form');
        $.post('<?= redirect_url('Donnee/ajx_insert') ?>', params, function(data){
            $('#return_zone').removeClass('hide');
            $('#return_text').html(data);
        });
    });
});
</script>