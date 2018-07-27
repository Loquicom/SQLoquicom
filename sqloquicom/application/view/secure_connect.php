<?php
defined('FC_INI') or exit('Acces Denied');
?>

<div style="margin-top: 40vh; transform: translateY(-50%);">
	<div class="row-fluid">
		<div class="col2"></div>
		<?php if($err === null){ ?>
			<div class="col8 alert a-is-info">
				<b>Indiquez le mot de passe pour continuer</b>
			</div>
		<?php } else { ?>
			<div class="col8 alert a-is-danger">
				<b><?= $err ?></b>
			</div>
		<?php } ?>
	</div>
	<form  name="secure_connect" method="POST">
		<div class="row-fluid" style="padding-top: 2em">
			<div class="col2"></div>
			<div class="col8">
				<div class="form-group">
		            <label for="pass">Mot de passe</label>
		            <input type="password" name="pass" class="form-element" id="pass" required>
		        </div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="col2"></div>
			<div class="col8">
				<div class="center">
		            <button type="submit" class="btn btn-primary main-color text-color">Valider</button>
		        </div>
			</div>
		</div>
	</form>
</div>