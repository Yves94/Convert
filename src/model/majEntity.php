<?php

class MajEntity
{
	private $id;
	private $date;
	private $description;

	function __construct($maj) {
		if (!is_null($maj)) {
			foreach ($maj as $key => $value) {
				$this->$key = $maj->$key;
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