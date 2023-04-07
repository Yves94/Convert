<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Le site de conversion qui vous permettra de convertir un grand nombre de mesure !" />
	<meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0" />
	<title><?php echo $title; ?></title>
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/public/css/main.css">
	<link rel="stylesheet" type="text/css" href="/vendor/ionicons/css/ionicons.min.css">
	<link rel="stylesheet" type="text/css" href="/public/css/flag-icon.min.css">
	<!-- JavaScript -->
	<script type="text/javascript" src="/public/js/jquery.min.js"></script>
	<script type="text/javascript" src="/public/js/main.js"></script>
	<!-- Logo -->
	<link rel="icon" type="image/x-icon" href="/public/img/logo.png" />
</head>
<body>

	<div id="header">
		<!-- Logo -->
		<div id="logo">
			<img src="/public/img/logo_large.png">
			<div><?php echo APP_NAME; ?></div>
		</div>

		<div id="beta" data-tooltip="Version en cours de developpement">ALPHA</div>

		<!-- Menu -->
		<div id="content">
			<?php if (isset($_SESSION['id'])): ?>
				<button id="logout"><span class="ion-power"></span></button>
			<?php endif; ?>

			<!-- Boutton de connexion -->
			<button <?php if (!isset($_SESSION['id'])): ?> id="connection" data-title="Connexion" data-url="/connexion" <?php else: ?> id="user" data-title="Compte" data-url="/compte" <?php endif; ?> >
				<?php if (!isset($_SESSION['id'])): ?>
					Connexion
				<?php else: ?>
					<?php echo $_SESSION['firstName'] .' '. $_SESSION['lastName']; ?>
				<?php endif; ?>
			</button>

			<button id="api" data-title="API" data-url="/api">API</button>

			<!-- <button id="history"><span class="ion-clock"></span></button> -->

			<!-- Chiffres significatifs (valeur 4 par défaut) -->
			<?php $number = (isset($_SESSION['sNumber'])) ? $_SESSION['sNumber'] : 4; ?>
			<select class="input select" id="significativeNumber">
				<?php for ($i = 1; $i < 10; $i++): ?>
					<option value="<?php echo $i ?>" <?php if ($i == $number): ?>selected<?php endif; ?>><?php echo $i; ?> chiffre(s) significatif(s)</option>
				<?php endfor; ?>
			</select>

			<!-- Champ de recherche -->
			<div id="search">
				<i class="ion-android-search"></i>
				<input type="text" class="input" placeholder="Recherche">
				<div id="tagList"></div>
			</div>
		</div>

	</div>

	<!-- Catégories -->
	<div id="hideScroll">
		<div id="category">
			<?php if (isset($categories)): ?>
				<?php foreach ($categories as $categorie): ?>
					<div class="nameCategory" data-category="<?php echo $categorie->uri; ?>">
						<div class="posCategory">
							<i class="<?php echo $categorie->icone; ?>"></i>
							<span class="libelleCategorie"><?php echo $categorie->libelle; ?></span>
							<?php if ($categorie->connect && !isset($_SESSION['id'])): ?>
								<span class="badge lock"><span class="ion-locked"></span></span>
							<?php else: ?>
								<span class="badge"><?php echo $categorie->count; ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<!-- Outils à appliqué sur les mesures -->
	<div id="tools">
		<div id="filtreTools"><span class="ion-funnel"></span><span class="filtreLabel">Filtres</span></div>
		<!-- <div id="sortTools"><span class="ion-gear-a"></span></div> -->
	</div>

	<!-- Corps du site -->
	<div id="container">
		<?php echo $container; ?>
	</div>

	<!-- Message pour les Cookies -->
	<div id="cookies">
		<p>En poursuivant votre navigation sur ce site, vous acceptez l’utilisation des <a href="javascript:void()">cookies</a> ainsi que notre <a href="javascript:void()">politique de données</a></p>
		<button style="margin-top: 5px;"><span class="ion-close-round"></span></button>
	</div>

	<!-- Pied de page -->
	<div id="footer">
		<i class="ion-information-circled footerSign"></i>
		<span>
			Contact: <b><a href="mailto:contact(at)myconverter(dot)net">contact at myconverter dot net</a></b><br>
			Tout droit réservé - Copyright 2016
		</span>
	</div>

	<!-- Fenêtre des messages -->
	<div id="boxMsg"></div>

	<!-- Fenêtre modale -->
	<div id="boxConfirm" class="box">
		<div class="box-content">
			<div class="box-title">Titre</div>
			<div class="box-body">Corp</div>
			<div class="box-footer">
				<input type="submit" class="btn-success" value="Oui">
				<input type="submit" class="btn-danger" value="Non">
			</div>
		</div>
	</div>

	<!-- Fenêtre alert -->
	<div id="boxAlert" class="box">
		<div class="box-content">
			<div class="box-title">Titre</div>
			<div class="box-body">Corp</div>
			<div class="box-footer">
				<input type="submit" class="input" value="OK">
			</div>
		</div>
	</div>

	<!-- Voile noir executé lors de l'ouverture d'une fenêtre modale -->
	<div id="blackBack"></div>

	<!-- Loader Ajax -->
	<div id="ajaxLoader">Veuillez patientez ...</div>

	<script type="text/javascript">
		var app_name = '<?php echo APP_NAME; ?>';
	</script>

</body>
</html>