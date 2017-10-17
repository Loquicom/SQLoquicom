<?php
defined('FC_INI') or exit('Acces Denied');
global $_config;
?>

<div class="container">
    <div class="row-fluid">
        <div class="col8">
            <a href="<?= redirect_url('Affichage') ?>" class="btn btn-default main-color text-color inline" title="Retour" style="margin-right: 1em;"><i class="material-icons">arrow_back</i></a>
            <h1 class="inline">Alter <?= $table ?></h1>
        </div>
        <div class="col4" style="text-align: right;">
            <button type="button" class="btn btn-default main-color text-color inline add_line" title="Ajouter une ligne" style="margin-right: 1em;"><i class="material-icons">add</i></button>
            <button type="button" class="btn btn-default main-color text-color inline valid_insert" title="Valider"><i class="material-icons">check</i></button>
        </div>
    </div>
    <hr>
    <div id="alter_form">
        <span class="btn-label main-color text-color">Alter 1</span><br>
        <div class="row-fluid">
            <div id="action-0" class="col12 action" data-num="0">
                <label>Action</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[0][action]" id="modif-0" value="1" checked>Modifier</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[0][action]" id="add-0" value="2">Ajouter</label>
                <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[0][action]" id="suppr-0" value="3">Supprimer</label>
            </div>
        </div>
        <div class="row-fluid" id="form-0">
            <div class="col4 form-group">
                <label style="position: relative;">Nom</label>
                <select type="text" name="chp[0][nom]" id="nom-0" class="form-element nom" data-num="0">
                    <option value="0">Selectionnez un champ</option>
                </select>
            </div>
            <div class="col4 form-group">
                <label style="position: relative;">Type</label>
                <input type="text" name="chp[0][type]" id="type-0" class="form-element">
            </div>
            <div class="col4 form-group">
                <label style="position: relative;">Defaut</label>
                <input type="text" name="chp[0][defaut]" id="defaut-0" class="form-element">
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
    $(document).ready(function () {
        var line = 1;

        //Ajout ligne
        $('.add_line').on('click', function () {
            var html = '<span class="btn-label main-color text-color">Alter ' + (line + 1) + '</span><br>' + "\n";
            html += '<div class="row-fluid">' + "\n";
            html += '   <div id="action-' + line + '" class="col12 action" data-num="' + line + '">' + "\n";
            html += '       <label>Action</label>' + "\n";
            html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em; margin-top: .5em;"><input type="radio" name="chp[' + line + '][action]" id="modif-' + line + '" value="1" checked>Modifier</label>' + "\n";
            html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[' + line + '][action]" id="add-' + line + '" value="2">Ajouter</label>' + "\n";
            html += '       <label style="font-weight: normal; display: inline-block; margin-right: 1em;"><input type="radio" name="chp[' + line + '][action]" id="suppr-' + line + '" value="3">Supprimer</label>' + "\n";
            html += '   </div>' + "\n";
            html += '</div>' + "\n";
            html += '<div class="row-fluid" id="form-' + line + '">' + "\n";
            html += '</div>' + "\n";
            $('#alter_form').append(html);
            $('#action-' + line).change();
            line++;
        });

        //Changement action
        $('#alter_form').on('change', '.action', function () {
            var num = $(this).attr('data-num');
            //Si c'est une modification
            if ($("#modif-" + num).is(":checked")) {
                //Modification du formulaire
                changeForm(1, num);
                //Chargement des noms des champs
                $.post('<?= redirect_url('Affichage/ajx_listChamp') ?>', {'table': '<?= $table ?>'}, function (data) {
                    if (data.etat == 'ok') {
                        var html = '';
                        $.each(data.champ, function (index) {
                            html += '<option value="' + data.champ[index] + '">' + data.champ[index] + '</option>' + "\n";
                        });
                        $("#nom-" + num).append(html);
                    }
                }, 'json');
            } else if ($("#add-" + num).is(":checked")) {
                //Modification du formulaire
                changeForm(2, num);
            } else if ($("#suppr-" + num).is(":checked")) {
                //Modification formulaire
                changeForm(3, num);
                //Chargement des noms des champs
                $.post('<?= redirect_url('Affichage/ajx_listChamp') ?>', {'table': '<?= $table ?>'}, function (data) {
                    if (data.etat == 'ok') {
                        var html = '';
                        $.each(data.champ, function (index) {
                            html += '<option value="' + data.champ[index] + '">' + data.champ[index] + '</option>' + "\n";
                        });
                        $("#nom-" + num).append(html);
                    }
                }, 'json');
            }
        });
        //Recup le type d'un champ pour modif
        $("#alter_form").on('change', '.nom', function () {
            var num = $(this).attr('data-num');
            $.post('<?= redirect_url('Affichage/ajx_typeChamp') ?>', {'table': '<?= $table ?>', 'champ': $(this).val()}, function (data) {
                console.log(data);
                    if (data.etat == 'ok') {
                        $("#type-" + num).val(data.type);
                        $("#defaut-" + num).val(data.defaut);
                    }
                }, 'json');
        });

        //Envoie
        $('.valid_insert').on('click', function () {
            var params = prepare_post('#alter_form');
            params.push({'name': 'table', 'value': '<?= $table ?>'});
            $.post('<?= redirect_url('Structure/ajx_alter') ?>', params, function (data) {
                $('#return_zone').removeClass('hide');
                $('#return_text').html(data);
            });
        });

        //Chargement du js adequat à l'action
        $('#action-0').change();
    });

    function changeForm(type, num) {
        if (type == 1) {
            var html = '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Nom</label>' + "\n";
            html += '       <select name="chp[' + num + '][nom]" id="nom-' + num + '" class="form-element nom" data-num="' + num + '"><option value="0">Selectionnez un champ</option></select>' + "\n";
            html += '   </div>' + "\n";
            html += '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Type</label>' + "\n";
            html += '       <input type="text" name="chp[' + num + '][type]" id="type-' + num + '" class="form-element">' + "\n";
            html += '   </div>' + "\n";
            html += '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Defaut</label>' + "\n";
            html += '       <input type="text" name="chp[' + num + '][defaut]" id="defaut-' + num + '" class="form-element">' + "\n";
            html += '   </div>' + "\n";
            $('#form-' + num).html(html);
        } else if (type == 2) {
            var html = '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Nom</label>' + "\n";
            html += '       <input type="text" name="chp[' + num + '][nom]" id="nom-' + num + '" class="form-element nom" data-num="' + num + '">' + "\n";
            html += '   </div>' + "\n";
            html += '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Type</label>' + "\n";
            html += '       <input type="text" name="chp[' + num + '][type]" id="type-' + num + '" class="form-element">' + "\n";
            html += '   </div>' + "\n";
            html += '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Defaut</label>' + "\n";
            html += '       <input type="text" name="chp[' + num + '][defaut]" id="defaut-' + num + '" class="form-element">' + "\n";
            html += '   </div>' + "\n";
            $('#form-' + num).html(html);
        } else if (type == 3) {
            var html = '   <div class="col4 form-group">' + "\n";
            html += '       <label style="position: relative;">Nom</label>' + "\n";
            html += '       <select name="chp[' + num + '][nom]" id="nom-' + num + '" class="form-element" data-num="' + num + '"><option value="0">Selectionnez un champ</option></select>' + "\n";
            html += '   </div>' + "\n";
            $('#form-' + num).html(html);
        }
    }
</script>