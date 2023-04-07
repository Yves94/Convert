<?php

class Request
{
	// Contient l'url entière
	public $url;
	// Contient la partie controller
	public $controller;
	// Contient la methode rataché au controller
	public $action;
	// Contient la liste des paramètres
	public $parameter;

	function __construct() {
		$this->url = UR;
	}

	// Retourne le paramètre souhaité de l'url
	public function getParameter($segment) {
		$segment--;
		if (isset($this->parameter[$segment])) {
			return $this->parameter[$segment];
		}
	}
}

?>