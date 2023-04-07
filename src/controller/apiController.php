<?php

class ApiController extends App
{
	// Retourne la page d'accueil
	public function indexAction() {

		if ($_GET) {

			$frame = $this->getRepository('mesure')->checkUserApi($_GET);
			echo $frame;

		} else {

			$title = 'API';
			$this->view('layout@api'. DS .'api', array('title' => $title));
		}
	}

	public function calculateAction() {
		if (count($_POST) >= 3)
		echo json_encode( $this->getRepository('mesure')->getMesureForApi($_POST) );
	}
}

?>