<?php

// Fichier contenant l'ensembles des routes avec leurs controllers/actions associés

// Accueil
Route::get('/', 'index@index');

// API
header('Access-Control-Allow-Origin: *');
Route::get('/api', 'api@index');
Route::get('/apiconvert', 'api@calculate');

// Connexion
Route::get('/connexion', 'index@login');
Route::get('/connect', 'index@connect');

// Inscription
Route::get('/inscription', 'index@register');
Route::get('/register', 'index@registerInfo');

// Compte
Route::get('/compte', 'index@account');

Route::get('/filtre', 'index@filtre');

// Admin Area
Route::get('/export', 'admin@export');

// Catégories
$categories = App::getRepository('categorie')->getAll();
foreach ($categories as $categorie) {
	Route::get('/'. $categorie->uri, 'index@category');
}

// Mesures
$mesures = App::getRepository('mesure')->getUriMesure();
foreach ($mesures as $mesure) {
	Route::get('/'. $mesure, 'index@category');
}

// Chiffres significatifs
if (isset($_POST['sNumber'])) { $_SESSION['sNumber'] = $_POST['sNumber']; }
if (!isset($_SESSION['sNumber'])) { $_SESSION['sNumber'] = 4; }

// Destruction d'une session
if (isset($_POST['logout'])) { session_destroy(); }
		
?>