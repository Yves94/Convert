<?php

class Init
{
	function __construct() {
		// Charge et parcours tous les fichiers du dossier 'app'
		spl_autoload_register(array($this, 'autoload'));
	}

	// Instancie toutes les class du dossier 'app'
	function autoload($class, $dir = null) {

		// Dans le cas ou le dossier est null
		if (is_null($dir)) {

			// Les class à chargées se trouve dans le dossier 'app'
			$dir = RP .'app'. DS;
		}

		// Pour chaque fichiers / dossiers trouvés
		foreach (scandir($dir) as $file) {

			// Dans le cas d'un dossier
			if (is_dir($dir . $file) && substr($file, 0, 1) !== '.') {

				// Appel de cette même fonction pour continuer la recherche
				$this->autoload($class, $dir . $file);
			}

			// Dans le cas d'un fichier 'php'
			if (preg_match('/.php$/i', $file)) {

				// Dans le cas d'un chemin aboutissant à un fichier
				if (substr($dir, -1, 1) != DS) {
					
					// Ce fichier est inclu
					require_once $dir . DS . $file;
				}
			}
		}
	}
}

?>