<?php
defined('FC_INI') or exit('Acces Denied');
?>

<div style="margin-top: 40vh; transform: translateY(-50%);">
	<div class="row-fluid">
		<div class="col2"></div>
		<?php if($err === null){ ?>
			<div id="alert_zone" class="col8 alert a-is-info">
				<b>Indiquez le nouveau mot de passe pour continuer</b>
			</div>
		<?php } else { ?>
			<div class="col8 alert a-is-danger">
				<b><?= $err ?></b>
			</div>
		<?php } ?>
	</div>
	<form id="secure_change" name="secure_change" method="POST">
		<div class="row-fluid" style="padding-top: 2em">
			<div class="col2"></div>
			<div class="col8">
				<div class="form-group">
		            <label for="pass">Ancien mot de passe</label>
		            <input type="password" name="pass" class="form-element" id="pass" required>
		        </div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="col2"></div>
			<div class="col8">
				<div class="form-group">
		            <label for="new_pass">Nouveau mot de passe</label>
		            <input type="password" name="new_pass" class="form-element" id="new_pass" required>
		        </div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="col2"></div>
			<div class="col8">
				<div class="form-group">
		            <label for="conf_new_pass">Confirmer le nouveau mot de passe</label>
		            <input type="password" name="conf_new_pass" class="form-element" id="conf_new_pass" required>
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

<script type="text/javascript">
	$(document).ready(function(){
		$("#secure_change").on('submit', function(){
			//Si pass différent
			if($("#new_pass").val() != $("#conf_new_pass").val()){
				$("#alert_zone").removeClass('a-is-info').addClass('a-is-danger').html('<b>Les mots de passes ne sont pas identiques</b>');
				return false;
			}
			return true;
		});
	});
</script>