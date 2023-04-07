<?php

class Route
{
	// Contient la requête url
	private static $request;

	// Récupération de l'url lors de l'instanciation
	function __construct($request) {
		self::$request = $request;
	}

	// Fonction permettant d'attribuer un controller et une action à une uri
	public static function get($uri, $controller) {

		// Url sans les paramètres
		$url = explode('?', self::$request->url)[0];
		
		// Dans le cas ou l'uri n'est pas trouvée
		if ($url != $uri) { return false; }
		
		// Récupération du controller et de l'action
		$pathAction = explode('@', $controller);

		// Définition du controller et de l'action dans l'objet request
		self::$request->controller = $pathAction[0] .'Controller';
		self::$request->action = $pathAction[1] .'Action';

		return true;
	}
}

?>