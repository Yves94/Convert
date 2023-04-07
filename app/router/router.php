<?php

class Router
{
	public $request;

	function __construct() {
		// Instanciation de la requête courante
		$this->request = new Request();

		// Parse de l'url pour identifier à quoi correspond chaque élément
		Parse::url($this->request);

		// Charge le controller
		$controller = $this->loadController();

		// Nom de l'action
		$action = $this->request->action;

		// Vérifie si l'action existe bien pour le controller appelé
		if (!in_array($action, get_class_methods($controller))) {
			$dev = 'Action [Route: '. $action .'] inconnue';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}
		
		// Appel de l'action demandée avec les paramètres
		call_user_func(array($controller, $action), $this->request);
	}

	private function loadController() {
		// Route du controller
		$controller = $this->request->controller;
		
		// Chemin vers le controller
		$file = RP . 'src' . DS . 'controller' . DS . $controller .'.php';

		// Dans le cas ou le fichier est inexistant
		if (!file_exists($file)) {
			$dev = 'Controller [Route: '. $controller .'] inconnu';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		// Inclu le fichier du controller demandé
		require $file;

		// Instancie le controller demandé
		return new $controller($this->request);
	}
}

?>