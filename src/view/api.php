<?php
// Change la couleur des commentaires PHP
ini_set('highlight.comment', '#75715E');
// Couleur des opérateurs
ini_set('highlight.keyword', '#e74c3c');
// Couleurs des chaines de caractères
ini_set('highlight.string', '#FFE792');
// Couleurs des variables et tableaux
ini_set('highlight.default', '#66D9EF');
?>

<span id="firstMsg">
	<p>L'API de <i><?php echo APP_NAME; ?></i> va vous permettre d'intégrer les mesures que vous souhaitez dans votre site Web, de façon très simple</p>
</span>

<div class="apiIndicatorPanel">
	<?php echo show_source('api-demo.php')[0]; ?>
</div>

<div class="apiInfoPanel">
	<?php if (!isset($_SESSION['id'])): ?>
		<div class="msg-error"><span class="ion-alert-circled"></span>&nbsp;Compte nécessaire pour utiliser l'API</div>
	<?php endif; ?>
	<div class="title">Explications</div>
	<p>Ce script PHP renvoi un tableau des mesures que vous aurez spécifiées dans la variable <code>$list</code></p>
	<p>Pour commencez, renseignez vos identifiants dans les variables <code>$email</code> et <code>$password</code></p>
	<p>A compléter ...</p>
</div>