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
    <div class="col2">
        <strong>Proposition :</strong>
    </div>
    <div class="col8 center" id="proposition">

    </div>
    <div class="col2">
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

        //Aide à la completion
        var lastWord;
        $('#requeteur').on('keyup', function (event) {
            //Envoie requete si shift + enter
            if(event.shiftKey && event.key == "Enter"){
                $('#send_requete').click();
            }
            //Recupération de la proposition de l'autocompletion
            else if (event.ctrlKey && event.key == " ") {
                $(this).val($(this).val().replace(new RegExp(lastWord + '$'), ''));
                $(this).val($(this).val() + $('#prop0').html());
                $('#proposition').html('');
                lastWord = '';
            } else if (event.altKey && (event.key == "&" || event.key == "é" || event.key == "\"" || event.key == "'" || event.key == "(")) {
                var prop = "#prop";
                if (event.key == "&") {
                    prop += "0";
                } else if (event.key == "é") {
                    prop += "1";
                } else if (event.key == "\"") {
                    prop += "2";
                } else if (event.key == "'") {
                    prop += "3";
                } else {
                    prop += "4";
                }
                $(this).val($(this).val().replace(new RegExp(lastWord + '$'), ''));
                $(this).val($(this).val() + $(prop).html());
                $('#proposition').html('');
                lastWord = '';
            }
            //Generation autocomplete    
            else {
                //Recup du dernier mot
                lastWord = $(this).val().split(" ");
                lastWord = lastWord[lastWord.length - 1];
                //Envoie au php pour resultat autocomplete
                if (lastWord.trim() != '') {
                    $.post('<?= redirect_url('Requeteur/ajx_autocomplete') ?>', {'search': lastWord}, function (data) {
                        if (data.length >= 1) {
                            $('#proposition').html('1 - <span id="prop0" style="padding-right: 2em;">' + data[0] + '</span>');
                            if (data.length >= 2) {
                                $('#proposition').append('2 - <span id="prop1" style="padding-right: 2em;">' + data[1] + '</span>');
                            }
                            if (data.length >= 3) {
                                $('#proposition').append('3 - <span id="prop2" style="padding-right: 2em;">' + data[2] + '</span>');
                            }
                            if (data.length >= 4) {
                                $('#proposition').append('3 - <span id="prop3" style="padding-right: 2em;">' + data[3] + '</span>');
                            }
                            if (data.length >= 5) {
                                $('#proposition').append('3 - <span id="prop4" style="padding-right: 2em;">' + data[4] + '</span>');
                            }
                        } else {
                            //Si aucun resultat on vide ceux deja affiché
                            $('#proposition').html('');
                        }
                    }, 'json');
                } else {
                    $('#proposition').html('');
                }
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