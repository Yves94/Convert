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
	<p>Le tableau <code>$config</code> permet de paramétrer le tableau renvoyé par l'API (taille des lignes, couleurs...)</p>
	<ul>
		<li><code>size</code> - Taille global en hauteur et largeur du tableau</li>
		<li><code>line_size</code> - Hauteur en pixel d'une ligne de conversion</li>
		<li><code>striped</code> - Booléen qui indique si l'alternance de couleur ou non pour chaque ligne</li>
		<li><code>description</code> - Booléen pour afficher la description associé aux mesures</li>
		<li><code>location</code> - Booléen pour afficher le pays d'origine de la mesure</li>
		<li><code>significative_number</code> - Nombre de chiffre significatif</li>
		<li><code>color</code> - Couleur des lignes (text, alternance de couleur 1, alternance de couleur 2)</li>
		<li><code>credit</code> - Affiche un lien vers le site <?php echo APP_NAME; ?></li>
	</ul>
	<p>L'instruction <code>$myConverter</code> permet d'envoyer toutes les informations spécifiées précédemment vers <?php echo APP_NAME; ?> pour traiter la demande et renvoyer le tableau avec les mesures souhaités</p>
</div>