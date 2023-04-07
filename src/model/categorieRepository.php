<?php

class CategorieRepository extends Repository
{
	public function getCategorie() {
		$query = $this->db->prepare('SELECT c.*, count(m.categorie) as count FROM '. $this->table .' as c, mesure as m WHERE m.categorie = c.id AND c.connect = 0 GROUP BY m.categorie ORDER BY c.libelle');
		$query->execute();

		// Retourne faux dans le cas ou aucun résultat n'est trouvé
		if (!$query->rowCount()) { return false; }

		$record = '';
		foreach ($query->fetchAll(PDO::FETCH_OBJ) as $object) {
			$record[] = new $this->entity($object);
		}

		return $record;
	}

	public function getStats() {

		$statTable = ['categorie', 'mesure', 'filtre'];

		foreach ($statTable as $value) {
			$query = $this->db->query('SELECT count(id) FROM '. $value);
			$row = $query->fetch();
			$stats[$value] = $row[0];
		}

		$jsonFile = file_get_contents(RP .'public'. DS .'js'. DS .'conversion.json');
		$nbConversion = json_decode($jsonFile);

		$stats['conversion'] = $nbConversion->count;

		return $stats;
	}

	// Retourne le tritre de la catégorie en fonction de l'uri
	public function getTitleByUri($category) {
		$query = $this->db->prepare('SELECT libelle FROM '. $this->table .' WHERE uri = :uri LIMIT 1');
		$query->execute(array(':uri' => $category));

		if (!$query->rowCount()) { return false; }

		return $query->fetch()[0];
	}
}

?>