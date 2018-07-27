<?php
defined('FC_INI') or exit('Acces Denied');
global $_config;
?>

<?php if (trim($err) != '') { ?>
    <div class="row-fluid alert a-is-danger" id="return_zone" style="margin-top: 1em; margin-bottom: 2em;">
        <i class="material-icons">info</i>
        <div class="col2"></div>
        <div class="col8" id="return_text">
            <?= $err ?>
        </div>
    </div>
<?php } ?>
<div class="row-fluid" style="margin-top: 2em;">
    <div class="col2"></div>
    <div class="col8">
        <form name="connect" method="POST">
            <div class="form-group">
                <label for="hote">Nom de l'hote</label>
                <input type="text" name="host" class="form-element" id="hote" placeholder="localhost" required>
            </div>
            <div class="form-group">
                <label for="base">Nom de la base</label>
                <input type="text" name="name" class="form-element" id="base" required>
            </div>
            <div class="form-group">
                <label for="user">Login</label>
                <input type="text" name="usr" class="form-element" id="user" placeholder="root" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" name="pass" class="form-element" id="mdp">
            </div>
            <?php if ($db !== null && !empty($db)) { ?>
                <div class="form-group">
                    <label for="db">Base enregistr&eacute;e<?= (count($db) > 1) ? 's' : ''; ?> :</label>
                    <select id="save_db" class="form-element">
                        <option value="0" id="default_val" selected>S&eacute;lectionnez une base</option>
                        <?php
                        foreach ($db as $name => $base) {
                            echo '<option value="' . $name . '">' . $base['host'] . ' : ' . $base['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="keep" style="margin-right: 1em;">Garder la base en mémoire sur le serveur
                </label>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="keep-cookie" style="margin-right: 1em;">Garder la base en mémoire sur ce navigateur
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
                $.post('<?= redirect_url('Connexion/ajx_readDB') ?>', {'db': $(this).val()}, function (data) {
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