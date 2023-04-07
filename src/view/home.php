<span id="firstMsg">
	<p>Bienvenue sur <i><?php echo APP_NAME; ?></i> ! Pour convertir, merci de cliquer sur une des catégories du menu de gauche</p>
</span>
<!-- Bloc des statistiques globales du site -->
<div class="indicatorContainer">
	<div class="indicatorPanel">
		<p class="title">Mesures</p>
		<p class="value" id="statsMesure"><?php echo $stats['mesure']; ?></p>
	</div>

	<div class="indicatorPanel">
		<p class="title">Catégories</p>
		<p class="value" id="statsCategorie"><?php echo $stats['categorie']; ?></p>
	</div>

	<div class="indicatorPanel">
		<p class="title">Filtres</p>
		<p class="value" id="statsFiltre"><?php echo $stats['filtre']; ?></p>
	</div>

	<div class="conversionNumber">
		<p>Conversions effectuées : &nbsp;<span id="statsConversion"><?php echo $stats['conversion']; ?></span></p>
	</div>
</div>

<!-- Bloc des sources et explications des symboles -->
<div class="infoContainer">
	<div class="infoPanel">
		<i>Sources des différentes mesures :</i>
		<table>
			<tr>
				<td><span class="ion-arrow-right-b"></span></td>
				<td>Wikipédia</td>
			</tr>
			<tr>
				<td><span class="ion-arrow-right-b"></span></td>
				<td>Référentiel des poids et mesures</td>
			</tr>
		</table>
		<br>
		<i>Explication des différents symboles :</i>
		<table>
			<tr>
				<td><span class="varExistInfo"><i class="ion-flag"></i></span></td>
				<td>Possibilité de modifier certaines variables</td>
			</tr>
			<tr>
				<td><span class="flag"><i class="ion-android-globe"></i></span></td>
				<td>Drapeau de l'origine de la mesure</td>
			</tr>
			<tr>
				<td><span class="description"><i class="ion-help"></i></span></td>
				<td>Informations sur la mesure</td>
			</tr>
			<tr>
				<td>≈</td>
				<td>Valeur très proche de 0</td>
			</tr>
		</table>
	</div>
</div>

<!-- Panel des MAJ -->
<div class="lastUpdatePanel">
	<i>Dernières mise à jours (version 0.1)</i>
	<table>
		<?php if (!$majs): ?>
			<p><span class="ion-alert"></span>&nbsp;Aucune mise à jour trouvée</p>
		<?php else: ?>
			<?php foreach ($majs as $maj): ?>
				<tr><td><?php echo date('d/m/Y', strtotime($maj->date)); ?></td><td><?php echo $maj->description; ?></td></tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
</div>