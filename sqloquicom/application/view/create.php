<?php
defined('FC_INI') or exit('Acces Denied');
global $_config;
?>

<div class="container">
    <div class="row-fluid">
        <div class="col8">
            <a href="<?= redirect_url('Affichage') ?>" class="btn btn-default main-color text-color inline" title="Retour" style="margin-right: 1em;"><i class="material-icons">arrow_back</i></a>
            <h1 class="inline">Create</h1>
        </div>
        <div class="col4" style="text-align: right;">
            <button type="button" class="btn btn-default main-color text-color inline add_line" title="Ajouter une ligne" style="margin-right: 1em;"><i class="material-icons">add</i></button>
            <button type="button" class="btn btn-default main-color text-color inline valid_insert" title="Valider"><i class="material-icons">check</i></button>
        </div>
    </div>
    <div id="create_form">
        <div class="row-fluid">
            <div class="col6 form-group">
                <label style="position: relative;">Nom de la table</label>
                <input type="text" name="table" class="form-element">
            </div>
        </div>
        <hr>
        <span class="btn-label main-color text-color">Champ 1</span><br>
        <div class="row-fluid">
            <div class="col4 form-group">
                <label style="position: relative;">Nom</label>
                <input type="text" name="chp[0][nom]" class="form-element">
            </div>
            <div class="col4 form-group">
                <label style="position: relative;">Type</label>
                <input type="text" name="chp[0][type]" class="form-element">
            </div>
            <div class="col4 form-group">
                <label style="position: relative;">Defaut</label>
                <input type="text" name="chp[0][defaut]" class="form-element">
            </div>
        </div>
        <div class="row-fluid">
            <div class="col2 form-group">
                <label>Null</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[0][null]" value="" checked>Oui</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[0][null]" value=" Not null">Non</label>
            </div>
            <div class="col2 form-group">
                <label>Auto Incriment</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[0][ai]" value=" Auto_increment">On</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[0][ai]" value="" checked>Off</label>
            </div>
            <div class="col2 form-group">
                <label>Clef Primaire</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="pk[0]" value="1">On</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="pk[0]" value="0" checked>Off</label>
            </div>
            <div class="col6 form-group">
                <label>Clef &Eacute;trang&egrave;re</label>
                <input type="text" name="chp[0][fk]" class="form-element fk" placeholder="Table(Champ)">
            </div>
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
        var html = '<span class="btn-label main-color text-color">Champ ' + (line + 1) + '</span><br>' + "\n";
        html += '<div class="row-fluid">' + "\n";
        html += '   <div class="col4 form-group">' + "\n";
        html += '       <label style="position: relative;">Nom</label>' + "\n";
        html += '       <input type="text" name="chp[' + line + '][nom]" class="form-element">' + "\n";
        html += '   </div>' + "\n";
        html += '   <div class="col4 form-group">' + "\n";
        html += '       <label style="position: relative;">Type</label>' + "\n";
        html += '       <input type="text" name="chp[' + line + '][type]" class="form-element">' + "\n";
        html += '   </div>' + "\n";
        html += '   <div class="col4 form-group">' + "\n";
        html += '       <label style="position: relative;">Defaut</label>' + "\n";
        html += '       <input type="text" name="chp[' + line + '][defaut]" class="form-element">' + "\n";
        html += '   </div>' + "\n";
        html += '</div>' + "\n";
        html += '<div class="row-fluid">' + "\n";
        html += '   <div class="col2 form-group">' + "\n";
        html += '       <label>Null</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[' + line + '][null]" value="" checked>Oui</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[' + line + '][null]" value=" Not null">Non</label>' + "\n";
        html += '   </div>' + "\n";
        html += '   <div class="col2 form-group">' + "\n";
        html += '       <label>Auto Incriment</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[' + line + '][ai]" value=" Auto_increment">On</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[' + line + '][ai]" value="" checked>Off</label>' + "\n";
        html += '   </div>' + "\n";
        html += '   <div class="col2 form-group">' + "\n";
        html += '       <label>Clef Primaire</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="pk[' + line + ']" value="1">On</label>' + "\n";
        html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="pk[' + line + ']" value="0" checked>Off</label>' + "\n";
        html += '   </div>' + "\n";
        html += '   <div class="col6 form-group">' + "\n";
        html += '       <label>Clef &Eacute;trang&egrave;re</label>' + "\n";
        html += '       <input type="text" name="chp[' + line + '][fk]" class="form-element fk" placeholder="Table(Champ)">' + "\n";
        html += '   </div>' + "\n";
        html += '</div>' + "\n";
        line++;
        $('#create_form').append(html);
    });
    
    $('.valid_insert').on('click', function(){
        var params = prepare_post('#create_form');
        $('.fk').each(function(){
            if($(this).val() == 'Table(Champ)'){
                $(this).val('');
            };
        });
        $.post('<?= redirect_url('Structure/ajx_create') ?>', params, function(data){
            $('#return_zone').removeClass('hide');
            $('#return_text').html(data);
        });
    });
});
</script>