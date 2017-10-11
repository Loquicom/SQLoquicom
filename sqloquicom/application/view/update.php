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
            <button type="button" class="btn btn-default main-color text-color inline valid_update" title="Valider"><i class="material-icons">check</i></button>
        </div>
    </div>
    <div id="update_form">
        <input type="hidden" name="table" value="<?= $table ?>">
        <?php 
        $i = 0;
        foreach ($lines as $line) { 
            ?>
            <span class="btn-label main-color text-color">Ligne <?= $i + 1 ?></span><br>
            <div class="row-fluid">
            <?php
            $j = 0;
            foreach ($line as $nom => $val){
                ?>
                <div class="col3 form-group">
                    <label for="hote" style="position: relative;"><?= $nom ?> <?= ($infos[$j]['Null'] == 'YES')?'[Null]':'' ?> <?= ($infos[$j]['Key'] == 'PRI')?'<i class="material-icons" style="position: absolute;">vpn_key</i>':'' ?></label>
                    <input type="text" name="<?= $nom ?>[<?= $i ?>]" class="form-element" value="<?= $val ?>" placeholder="<?= $infos[$j]['Type'] ?>" <?= ($infos[$j]['Key'] == 'PRI')?'disabled':'' ?>>
                </div>
                <?php 
                if($infos[$j]['Key'] == 'PRI'){
                    ?>
                    <input type="hidden" name="pk[<?= $i ?>][<?= $nom ?>]" value="<?= $val ?>">
                    <?php
                }
                $j++;
            }
            ?>
            </div>
            <?php
            $i++;
        }
        ?>
    </div>
    <div class="row-fluid">
        <div class="col12" style="text-align: right;">
            <button type="button" class="btn btn-default main-color text-color inline valid_update" title="Valider"><i class="material-icons">check</i></button>
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
    $('.valid_update').on('click', function(){
        var params = prepare_post('#update_form');
        $.post('<?= redirect_url('Donnee/ajx_update') ?>', params, function(data){
            $('#return_zone').removeClass('hide');
            $('#return_text').html(data);
        });
    });
});
</script>