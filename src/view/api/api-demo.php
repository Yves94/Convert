<?php
/*****************************************/
/** Code PHP permettant d'appeler l'API **/
/*****************************************/

// Identifiants de connexion
$email = 'mon_adresse_mail';
$password = 'mon_mot_de_passe';

// Liste des mesures
$list = [1, 4, 2, 3];

// Tableau de configuration
$config = [
	'size' => [
		'width' => '350',
		'height' => '150'
	],
	'line_size' => 30,
	'striped' => true,
	'description' => false,
	'location' => false,
	'significative_number' => 4,
	'color' => [
		'color_text' => '#fff',
		'color_line1' => '',
		'color_line2' => ''
	],
	'credit' => true
];

// Appel de l'url
$myConverter = file_get_contents(
	'http://192.168.1.72:8008/api?'.
	'email='. $email .'&'.
	'password='. $password .'&'.
	'list='. base64_encode(serialize($list)) .'&'.
	'config='. base64_encode(serialize($config))
);

// Affichage des mesures retournées
echo $myConverter;
?>