<?php

class NewException extends Exception
{
	function __construct($code, $msg_dev, $message) {
		// Affichage des deux messages en mode developpement
		if (!PRODUCTION) {
			$message = $msg_dev .'<br>'. $message;
		}

		parent::__construct($message, $code);
	}

	public function __toString() {
		// Formatage du header
		header('HTTP/1.0 '. $this->getCode() .' '. $this->getHtmlCode());

		// En cas d'une maintenance
		if (MAINTENANCE) {
			// Chemin vers la page de maintenance
			require_once RP .'src'. DS .'view'. DS .'main'. DS . VIEW_MAINTENANCE;
			return '';
		}
		
		// Enclenche la temporisation de sortie
		ob_start();

		// Définition dans la vue d'erreur
		$title = APP_NAME .' - Erreur '. $this->getCode();
		// Affichage du message d'erreur
		$container = '<div class="msg-error">'. $this->getMessage() .'</div>';
		// Récupère les catégories pour former le menu vertical de gauche
		$categories = App::getRepository('categorie')->getCategorie();

		// Envoie les données du tampon de sortie et éteint la temporisation de sortie
		ob_end_flush();

		// Chemin vers la page d'erreur
		require_once RP .'src'. DS .'view'. DS .'main'. DS . VIEW_ERROR;

		return '';
	}

	public function getHtmlCode() {
		switch ($this->getCode()) {
	        case 403: return 'Forbidden';
	        case 404: return 'Not Found';
	        case 500: return 'Internal Server Error';
	        case 503: return 'Service Unavailable';
		}
	}
}

?>