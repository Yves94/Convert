<?php

class CategorieEntity
{
	private $id;
	private $libelle;
	private $reference;
	private $icone;
	private $description;
	private $uri;
	private $connect;
	private $count;

	function __construct($category) {
		if (!is_null($category)) {
			foreach ($category as $key => $value) {
				$this->$key = $category->$key;
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