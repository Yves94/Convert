<?php

class UtilisateurEntity
{
	private $id;
	private $nom;
	private $prenom;
	private $login;
	private $password;
	private $admin;

	function __construct($user) {
		if (!is_null($user)) {
			foreach ($user as $key => $value) {
				$this->$key = $user->$key;
			}
		}
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}
}

?>