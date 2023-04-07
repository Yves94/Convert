<?php if (isset($_SESSION['id'])): ?>

	<div id="account">

		<!-- Zone comportant les informations relatives au compte -->
		<div id="profile">
			<div class="userTitle"><span class="ion-person"></span>&nbsp;Mon compte</div>
			<form method="post" action="request.php">
				<input type="hidden" name="action" value="register">
				<div class="input-group">
					<span class="ion-person"></span>
					<input type="text" class="input" name="nom" placeholder="Nom" value="<?php echo $_SESSION['lastName']; ?>" required>
				</div>
				<div class="input-group">
					<span class="ion-person"></span>
					<input type="text" class="input" name="prenom" placeholder="Prénom" value="<?php echo $_SESSION['firstName']; ?>" required>
				</div>
				<div class="input-group">
					<span class="ion-email"></span>
					<input type="email" class="input" name="login" placeholder="Email" value="<?php echo $_SESSION['mail']; ?>" required>
				</div>
				<div style="text-align: center;">
					<input type="submit" value="Modifier">
				</div>
			</form>
			<br>
			<div class="userTitle"><span class="ion-locked"></span>&nbsp;Mot de Passe</div>
			<form method="post" action="request.php">
				<div class="input-group">
					<span class="ion-forward"></span>
					<input type="password" class="input" name="password_old" placeholder="Mot de Passe actuel" required>
				</div>
				<div class="input-group">
					<span class="ion-key"></span>
					<input type="password" class="input" name="password" placeholder="Nouveau Mot de Passe" required>
				</div>
				<div class="input-group">
					<span class="ion-key"></span>
					<input type="password" class="input" name="confirm_password" placeholder="Confirmation" required>
				</div>
				<div style="text-align: center;">
					<input type="submit" value="Modifier">
				</div>
			</form>
		</div>

		<!-- Zone de proposition de mesure -->
		<div id="feedBack">
			<div class="userTitle"><span class="ion-edit"></span>&nbsp;Proposer une mesure</div>
			<form>
		    	<div class="input-group">
					<span class="ion-clipboard"></span>
					<select class="input select" name="categorie" style="width:51%; margin-bottom: 10px; padding-left: 30px;" required>
						<option><i>Catégorie</i></option>
					</select>
				</div>
		    	<div class="input-group">
					<span class="ion-arrow-swap"></span>
					<input type="text" class="input" name="unite" placeholder="Unité" required>
				</div>
				<div class="input-group">
					<span class="ion-quote"></span>
					<input type="text" class="input" name="abrege" placeholder="Abréviation" required>
				</div>
				<div class="input-group">
					<span class="ion-calculator"></span>
					<input type="text" class="input" name="formule" placeholder="Formule" required>
				</div>
		        <div class="input-group">
		        	<textarea class="input" name="description" placeholder="Description"></textarea>
		        </div>

		        <div style="text-align: center;">
			        <input type="button" value="Reset" class="reset" style="width: 25%;">
			        <input type="submit" class="btn-success" value="Proposer" style="width: 25%;">
		    	</div>
	    	</form>
	    	<?php if ($_SESSION['admin']): ?>
		    	<br>
				<div class="userTitle"><span class="ion-android-upload"></span>&nbsp;Importer un fichier</div>
				<div class="input-group">
					<input type="file" class="input" name="bdd">
				</div>
				<div style="text-align: center;">
					<input type="submit" value="Importer">
				</div>
				<br>
				<div class="userTitle"><span class="ion-android-download"></span>&nbsp;Exporter un fichier</div>
				<div style="text-align: center;">
					<input type="submit" value="Exporter" rel="nofollow" onclick="window.location='/export'">
				</div>
			<?php endif; ?>
		</div>

	</div>

<?php else: ?>
	<!-- Session expirée -->
	<script type="text/javascript">
		localStorage.setItem('expired', 1);
		ChangeUrl('ConvertApp', getDomainUrl());
		location.reload();
	</script>
	
<?php endif; ?>