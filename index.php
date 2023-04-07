<?php

// Ouverture d'une session
session_start();

// --- Variables globales ---

// Séparateur adapté à l'OS courant (Directory Separator)
define('DS', DIRECTORY_SEPARATOR);
// Définition du chemin racine (Root Path)
define('RP', dirname(__FILE__) . DS);
// Définition du domaine (Url Base)
define('UB', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
// Définition des uris (Url Request)
define('UR', str_replace(UB, '', $_SERVER['REQUEST_URI']));
// Définition de l'OS courant
define('OS', strtoupper(substr(PHP_OS, 0, 3)));

// --- Initialisation des paramètres ---

// Acquisition du fichier d'instanciation des class
require RP .'app'. DS .'init.php';
// Instanciation de ce dernier
new Init();

// Lecture du fichier de configuration
Parse::ini('config.ini');

// Renvoi les messages d'erreur PHP (seulement hors mode production)
ini_set('display_errors', !PRODUCTION);

// Test si maintenance
if (!MAINTENANCE) {

	// Dispatching
	new Router();

} else {

	// Exception retournée
	die(new NewException(503, '', ''));
}

?>