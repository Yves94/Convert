<?php

class Database
{
	public $db;
	private static $singleton = NULL;

	function __construct() {
		// Par défaut, appel de la connexion mySQL
		$this->mySql();
	}

	// Permet de creer l'instance une seule fois
	public static function singleton() {
		// Dans le cas ou la class n'est pas déja instanciée
		if (is_null(self::$singleton)) {
			// Instanciation de la class 'Database'
			self::$singleton = new self();
		}
		// Retourne la class instanciée
		return self::$singleton;
		
	}

	// Connexion MySQL
	private function mySql() {
		// Tentative de connexion
		try {
			$db = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode=""']);
			$this->db = $db;
		}
		// En cas d'erreur, une exception est renvoyé
		catch (PDOException $e) {
			$dev = $e;
			$prod = 'Erreur de connexion à la base de données';
			die(new NewException(404, $dev, $prod));
		}
	}
}

?>