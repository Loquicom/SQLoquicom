<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_config;
?>

<div class="row-fluid">
    <div class="col12">
        <div class="form-group">
            <label for="hote">Requeteur :</label>
            <textarea class="form-element" id="requeteur" style="height: 10em;"></textarea>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="col 12">
        <div style="text-align: right">
            <button type="button" id="send_requete" class="btn btn-primary main-color text-color">Envoyer</button>
        </div>
    </div>
</div>

<!-- Zone de retour hors select -->
<div id="return_info">
</div>

<!-- Zone de retour select -->
<div class="row-fluid" style="margin-top: 3em; margin-bottom: 3em;">
    <div class="col12">
        <div id="return">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //Mise auto des double quotes
        /*
        $('#requeteur').on('keydown', function (keypress) {
            if (keypress.key == "'") {
                $(this).val($(this).val() + "'");
            }
        });
        //*/

        $('#send_requete').on('click', function () {
            //Remise a zero des zone d'affichage
            $('#return_info').html('');
            $('#return').html('');
            //Envoie de la requete en ajax
            $.post('<?= $_config['web_root'] ?>Requeteur/ajx_requete', {'requete': $('#requeteur').val()}, function (data) {
                console.log(data.view);
                if(data.etat === 'ok'){
                    //Si il y a un tableau a afficher
                    if(data.view.length > 0){
                        for(i = 0; i < data.view.length; i++){
                            $('#return').html($('#return').html() + data.view[i]);
                        };
                    }
                    //Si il y a des infos 
                    if(data.info.trim() != ''){
                        $('#return_info').html(data.info);
                        $('#return_info').removeClass('hide');
                    }
                }
            }, 'json');
        });
    });
</script>