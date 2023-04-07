<?php

class Parse
{
	// Transpose un fichier .ini en variable globale
	public static function ini($file) {
		// Dans le cas ou le fichier est lisible
		if (is_readable($file)) {

			// Fabrication du tableau
			$iniArray = parse_ini_file($file);

			// Boucle sur chaque element du tableau
			foreach ($iniArray as $name => $value) {

				// Définit les variables globales pour chaque éléments trouvés
				define(strtoupper($name), $value);
			}
			return true;
		}
		return false;
	}

	// Identifie dans l'url les différents éléments (controller, action, paramètres)
	public static function url($request) {

		// Suppression des '/' en fin/début d'url
		$url = trim($request->url, '/');

		// Contient l'url découpé avec une partie chemin et une partie paramètres
		$detailUrl = parse_url($url);

		// Définition du tableau qui va contenir nos deux parties
		$formatUrl = [];

		// Découpages des segments en uri dans la partie chemin
		$formatUrl['path'] = explode('/', $detailUrl['path']);

		// Dans le cas ou une partie paramètre existe
		if (isset($detailUrl['query'])) {
			
			// Découpage de la partie paramètres
			parse_str($detailUrl['query'], $output);
			$formatUrl['query'] = $output;
		}

		// Renvoi du parse dans l'attribut parameter
		$request->parameter = $formatUrl;

		// Instancie la class des routes
		new Route($request);

		// Appel du fichier contenant les routes définies
		require RP .'src'. DS .'routes.php';

		// Dans le cas ou le controller ou l'action n'est pas défini (ou mal défini)
		$request->controller = empty($request->controller) ? $request->url : $request->controller;
		$request->action = empty($request->action) ? $request->url : $request->action;

		return true;
	}
}

?>