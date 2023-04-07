<?php

class Repository
{
	// Objet PDO
	public $db;
	// Nom de l'entité
	public $entity;
	// Nom du repository
	public $repository;
	//Nom de la table
	public $table;

	function __construct($table) {
		// Connexion à la base de données
		$db = Database::singleton();
		// Enregistrement de la connexion courante
		$this->db = $db->db;
		// Remplace le suffixe pour avoir le nom de l'entity
		$this->entity = str_replace('Repository', 'Entity', get_called_class());
		// Nom du repository
		$this->repository = get_called_class();
		// Nom de la table
		$this->table = $table;
	}

	public function getAll($order = null, $limit = null) {
		$clause = '';
		// Préparation des clauses 'order by' et 'limit'
		($order) ? $clause .= ' order by '. $order .' ' : null;
		($limit) ? $clause .= ' limit '. $limit .' ' : null;

		// Requête de sélection de tous les enregistrements d'une table
		$query = $this->db->prepare('select * from '. $this->table . $clause);
		$query->execute();

		// Retourne faux dans le cas ou aucun résultat n'est trouvé
		if (!$query->rowCount()) { return false; }

		$record = '';
		foreach ($query->fetchAll(PDO::FETCH_OBJ) as $object) {
			$record[] = new $this->entity($object);
		}

		return $record;
	}
}

?>