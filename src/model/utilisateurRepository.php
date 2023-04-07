<?php

class UtilisateurRepository extends Repository
{
	public function verify($email, $mdp) {
		$msg = '';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-z0-9_-]{6,18}$/', $mdp)) {
			$msg['error'] = 'Identifiants incorrects';
			return $msg;
		}

		$query = $this->db->prepare('SELECT * FROM '. $this->table .' WHERE login = :login AND password = :password LIMIT 1');
		$query->execute(array(':login' => $email, ':password' => md5($mdp)));

		if (!$query->rowCount()) {
			$msg['error'] = 'Identifiants incorrects';
			return $msg;
		}

		$user = $query->fetchAll(PDO::FETCH_OBJ)[0];
		new $this->entity($user);

		$_SESSION['id'] = $user->id;
		$_SESSION['firstName'] = $user->prenom;
		$_SESSION['lastName'] = $user->nom;
		$_SESSION['mail'] = $user->login;
		$_SESSION['admin'] = $user->admin;

		$msg['success'] = 'Connexion avec succès';

		return $msg;
	}

	public function verifyForm($data) {
		$msg = '';

		$data['prenom'] = ucfirst(strtolower($data['prenom']));
		if (!preg_match('/^[a-zA-Z]{2,50}$/', $data['prenom'])) {
			$msg['error'] = 'Prénom incorrect';
			return $msg;
		}

		$data['nom'] = strtoupper($data['nom']);
		if (!preg_match('/^[A-Z]{2,50}$/', $data['nom'])) {
			$msg['error'] = 'Nom incorrect';
			return $msg;
		}

		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$msg['error'] = 'Adresse email incorrect';
			return $msg;
		}

		$query = $this->db->prepare('SELECT * FROM '. $this->table .' WHERE login = :email LIMIT 1');
		$query->execute(array(':email' => $data['email']));

		if ($query->rowCount() != 0) {
			$msg['error'] = 'Adresse email non disponible';
			return $msg;
		}

		if (!preg_match('/^[a-z0-9_-]{6,18}$/', $data['mdp'])) {
			$msg['error'] = 'Mot de passe non conforme';
			return $msg;
		}

		if ($data['mdp'] != $data['mdp_confirm']) {
			$msg['error'] = 'Confirmation incorrect';
			return $msg;
		}

		$query = $this->db->prepare('INSERT INTO '. $this->table .' VALUES (null, :nom, :prenom, :email, :password, 0)');
		$result = $query->execute(array(':nom' => $data['nom'], ':prenom' => $data['prenom'], ':email' => $data['email'], ':password' => md5($data['mdp'])));

		if (!$result) {
			$msg['error'] = 'Une erreur est survenue';
		}
		
		return $msg;
	}
}

?>