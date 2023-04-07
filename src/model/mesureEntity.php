<?php

class MesureEntity
{
	private $id;
	private $categorie;
	private $unite;
	private $abrege;
	private $formule;
	private $formule_inverse;
	private $pays;
	private $description;
	private $actif; // Mesure visible tout public (bool)
	private $value;
	private $uri; // Uri de la mesure
	private $filtre; // Filtres associés
	private $variable; // Mesure contenant des variables (bool)

	function __construct($mesure) {
		if (!is_null($mesure)) {
			foreach ($mesure as $key => $value) {
				$this->$key = $mesure->$key;
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