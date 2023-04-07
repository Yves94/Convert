<div id="connectForm">
	<form method="post" action="/register" id="register">
		<div class="input-group">
			<span class="ion-person"></span>
			<input type="text" class="input" name="prenom" placeholder="Prénom">
		</div>
		<div class="input-group">
			<span class="ion-person"></span>
			<input type="text" class="input" name="nom" placeholder="Nom" style="text-transform:uppercase">
		</div>
		<div class="input-group">
			<span class="ion-email"></span>
			<input type="email" class="input" name="email" placeholder="Email">
		</div>
		<div class="input-group">
			<span class="ion-key"></span>
			<input type="password" class="input" name="mdp" placeholder="Mot de Passe">
		</div>
		<div class="input-group">
			<span class="ion-key"></span>
			<input type="password" class="input" name="mdp_confirm" placeholder="Confirmation">
		</div>
		<button>S'inscrire</button>
	</form>
	<a class="register" href="/connexion">Se connecter</a>
</div>