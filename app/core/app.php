<?php

class App
{
	public $request;

	public function __construct($request) {
		$this->request = $request;
	}

	// Permet d'envoyer des variables à une vue
	public function view($view, $variables = null) {
		// Extrait les variables
		if ($variables) {
			extract($variables);
		}

		// Dans le cas ou une variable erreur est transmise
		$error = (isset($error)) ? $error : 'Aucune donnée trouvée !';

		// Vérifie si il existe une partie layout et vue
		if (strpos($view, '@')) {
			// Récupère la vue et le layout
			$view = explode('@', $view);
		} else {
			$dev = 'Couple Layout@Vue ['. $view .'] introuvable';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		// Chemain vers la vue
		$file = RP .'src'. DS .'view'. DS . $view[1] .'.php';

		// En cas de fichier non trouvé, une exception est renvoyé
		if (!file_exists($file)) {
			$dev = 'Vue ['. $view[1] .'] introuvable';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		// Temporisation pour inclure la vue dans le layout
		ob_start();
		// Vue demandée
		require $file;
		// Intégration de la vue dans le layout
		$container = ob_get_clean();

		// Formate le titre dans l'onglet
		$title = (isset($title)) ? APP_NAME .' - '. $title : APP_NAME;

		// Récupère les catégories pour former le menu vertical de gauche
		$categories = $this->getRepository('categorie')->getCategorie();

		// Chemin vers le layout
		$file = RP .'src'. DS .'view'. DS .'main'. DS . $view[0] .'.php';

		// En cas de fichier non trouvé, une exception est renvoyé
		if (!file_exists($file)) {
			$dev = 'Layout ['. $view[0] .'] introuvable';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		require $file;
	}

	// Charge le dépot / entité demandé
	public static function getRepository($name) {
		// Nom des fichiers
		$entity = $name .'Entity';
		$repository = $name .'Repository';

		// Chemin vers Entity et Repository concerné
		$fileEntity = RP .'src'. DS .'model'. DS . $entity .'.php';
		$fileRepository = RP .'src'. DS .'model'. DS . $repository .'.php';

		// Test de l'existance des fichiers, sinon envoi d'une exception
		if (!file_exists($fileEntity) || !file_exists($fileRepository)) {
			$dev = 'Entité ['. $entity .'] ou dépôt ['. $repository .'] introuvable';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		// Dans le cas ou la class n'existe pas, il y a définition des chemins vers la class
		if (!class_exists($repository)) {
			
			// Inclusion des fichiers Entity et Repository
			require $fileEntity;
			require $fileRepository;
		}

		// Instanciation du Repository
		return new $repository($name);	
	}
}

?>