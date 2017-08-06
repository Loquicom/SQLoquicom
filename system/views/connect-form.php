<?php
(defined('APPLICATION')) ? '' : exit('Acces denied');
global $_config;
?>

<!--form name="connect" method="POST">
    <input type="text" name="host">
    <input type="text" name="name">
    <input type="text" name="usr">
    <input type="password" name="pass">
    <button type="submit">Connexion</button>
</form-->

<div class="container">
    <div class="col2"></div>
    <div class="col8">
        <form name="connect" method="POST">
            <div class="form-group">
                <label for="hote">Nom de l'hote</label>
                <input type="text" name="host" class="form-element" id="hote" placeholder="localhost">
            </div>
            <div class="form-group">
                <label for="base">Nom de la base</label>
                <input type="text" name="name" class="form-element" id="base">
            </div>
            <div class="form-group">
                <label for="user">Login</label>
                <input type="text" name="usr" class="form-element" id="user" placeholder="root">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" name="pass" class="form-element" id="mdp">
            </div>
            <?php if ($db !== null) { ?>
                <div class="form-group">
                    <label for="db">Base enregistr&eacute;e<?= (count($db) > 1) ? 's' : ''; ?> :</label>
                    <select id="save_db" class="form-element">
                        <option value="0" id="default_val" selected>S&eacute;lectionnez une base</option>
                        <?php
                        foreach ($db as $base) {
                            $host = explode("_", $base)[0];
                            $name = str_replace('.dat', '', explode("_", $base)[1]);
                            echo '<option value="' . $base . '">' . $host . ' : ' . $name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="keep" style="margin-right: 1em;">Garder la base en mémoire
                </label>
            </div>
            <div class="center">
                <button type="submit" class="btn btn-primary main-color text-color">Connexion</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#save_db').on('change', function () {
            if ($(this).val() != 0) {
                $.post('<?= $_config['web_root'] ?>', {'db': $(this).val()}, function (data) {
                    if (data.etat == 'ok') {
                        $('#hote').val(data.hote);
                        $('#base').val(data.base);
                        $('#user').val(data.user);
                        $('#mdp').val(data.mdp);
                    } else {
                        $('#save_db').children('option[selected]').remove();
                        $('#default_val').prop('selected', true);
                    }
                }, 'json');
            }
        });
    });
</script>