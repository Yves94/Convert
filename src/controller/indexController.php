<?php

class IndexController extends App
{
	// Retourne la page d'accueil
	public function indexAction() {
		$title = 'Accueil';
		// Récupère les statistiques
		$stats = $this->getRepository('categorie')->getStats();
		// Récupère les mis à jour
		$majs = $this->getRepository('maj')->getAll('date desc', 10);

		$this->view('layout@home', array('stats' => $stats, 'majs' => $majs, 'title' => $title));
	}

	// Affiche les mesures d'une catégorie
	public function categoryAction() {
	
		$category = $this->request->parameter['path'][0];
		$mesure = (isset($this->request->parameter['path'][1])) ? $this->request->parameter['path'][1] : null;

		if ($category == 'cryptographie' && !isset($_SESSION['id'])) {
			$this->view('layout@mesure', array('error' => 'Connexion requise'));
			return false;
		}

		$titleMesure = ($mesure) ? ' - '. $this->getRepository('mesure')->getTitleByUri($mesure) : '';
		
		$title = $this->getRepository('categorie')->getTitleByUri($category) . $titleMesure;

		$mesures = $this->getRepository('mesure')->getMesureByCategory($category);

		$idMesure = ($mesure) ? $this->getRepository('mesure')->getIdByUri($mesure) : null;

		$this->getRepository('mesure')->calculValue($category, $mesures, $idMesure);
		
		$this->view('layout@mesure', array('mesures' => $mesures, 'title' => $title));
		
	}

	public function loginAction() {
		$title = 'Connexion';
		$this->view('layout@connect', array('title' => $title));
	}

	public function connectAction() {

		$msg = '';

		if (!isset($_POST['email']) || !isset($_POST['mdp'])) {
			$msg['error'] = 'Formulaire corrompu';
		} else {
			$user = $this->getRepository('utilisateur');
			$msg = $user->verify($_POST['email'], $_POST['mdp']);
		}
		
		echo json_encode($msg);
	}

	public function registerAction() {
		$title = 'Inscription';
		$this->view('layout@register', array('title' => $title));
	}

	public function registerInfoAction() {
		$msg = '';

		if (!isset($_POST['email']) || !isset($_POST['mdp']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['mdp_confirm'])) {
			$msg['error'] = 'Formulaire corrompu';
		} else {
			$user = $this->getRepository('utilisateur');
			$msg = $user->verifyForm($_POST);
		}
		
		echo json_encode($msg);
	}

	public function filtreAction() {
		if (isset($_POST['category']) && $_POST['category'] == '') { return false; }
		$category = (isset($_POST['category'])) ? $_POST['category'] : 0;
		if (!$category) {
			$dev = 'Aucun paramètre POST';
			$prod = 'Adresse URL incorrect';
			die(new NewException(404, $dev, $prod));
		}

		$filtres = $this->getRepository('mesure')->getFilterByCategory($category);

		return $this->view('layout@filtre', array('filtres' => $filtres));
	}

	public function accountAction() {
		$title = 'Mon Compte';

		return $this->view('layout@account', array('title' => $title));
	}
}

?>