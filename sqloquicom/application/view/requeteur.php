<?php
defined('FC_INI') or exit('Acces Denied');
?>

<div class="row-fluid">
    <div class="col12">
        <div class="form-group">
            <label for="hote">Requeteur :</label>
            <textarea class="form-element" id="requeteur" placeholder="Requete SQL ou fichier SQL" style="height: 10em;"></textarea>
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
    <div class="col12" style="overflow-x: auto; width: 100%">
        <div id="return">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //Envoie Requete
        $('#send_requete').on('click', function () {
            //Remise a zero des zone d'affichage
            $('#return_info').html('');
            $('#return').html('');
            //Envoie de la requete en ajax
            $.post('<?= redirect_url('Requeteur/ajx_requete') ?>', {'requete': $('#requeteur').val()}, function (data) {
                if (data.etat === 'ok') {
                    //Si il y a un tableau a afficher
                    if (data.view.length > 0) {
                        for (i = 0; i < data.view.length; i++) {
                            $('#return').html($('#return').html() + data.view[i]);
                        }
                    }
                    //Si il y a des infos 
                    if (data.info.trim() != '') {
                        $('#return_info').html(data.info);
                        $('#return_info').removeClass('hide');
                    }
                    //Changement couleur textarea
                    $('#requeteur').css('border-color', '#00c42a');
                    $('#requeteur').css('box-shadow', '0 0 8px rgba(0, 196, 42, 0.6)');
                }
            }, 'json');
        });

        //Suppression couleur si requeteur vide
        $('#requeteur').on('focusout', function () {
            if ($(this).val().trim() == '') {
                $('#requeteur').css('border-color', '');
                $('#requeteur').css('box-shadow', '');
            }
        });
    });

    //Lecture fichier drang&drop
    $('#requeteur').on('dragenter', function () {
        $(this).css('border-color', '#ffc107');
        $(this).css('box-shadow', '0 0 8px rgba(255, 193, 7, 0.6)');
        return false;
    });

    $('#requeteur').on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border-color', '#ffc107');
        $(this).css('box-shadow', '0 0 8px rgba(255, 193, 7, 0.6)');
        return false;
    });

    $('#requeteur').on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border-color', '');
        $(this).css('box-shadow', '');
        return false;
    });

    $('#requeteur').on('drop', function (e) {
        if (e.originalEvent.dataTransfer) {
            if (e.originalEvent.dataTransfer.files.length) {
                // Stop the propagation of the event
                e.preventDefault();
                e.stopPropagation();
                // Main function to upload
                upload(e.originalEvent.dataTransfer.files);
            }
        }
        return false;
    });

    function upload(files) {
        var f = files[0];
        var reader = new FileReader();
        // When the image is loaded,
        // run handleReaderLoad function
        reader.onload = handleReaderLoad;
        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }

    function handleReaderLoad(evt) {
        var pic = {};
        pic.file = evt.target.result.split(',')[1];
        var str = jQuery.param(pic);
        $.ajax({
            type: 'POST',
            url: '<?= redirect_url('Requeteur/ajx_read_file') ?>',
            data: str,
            success: function (data) {
                if (data.trim != '') {
                    //On affiche le sql et on execute
                    $('#requeteur').val(data);
                    $('#send_requete').click();
                }
            }
        });
    }

</script>