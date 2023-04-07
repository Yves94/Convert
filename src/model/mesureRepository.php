<?php

class MesureRepository extends Repository
{
	// Renvoi un tableau contenant toutes les mesures pour une catégorie
	public function getMesureByCategory($category) {
		$query = $this->db->prepare('SELECT m.*, GROUP_CONCAT(f.nature,":",f.libelle,":",fm.id_filtre) AS filtre, u.uri FROM '. $this->table .' as m LEFT JOIN categorie as c ON c.id = m.categorie  LEFT JOIN `filtre/mesure` as fm ON fm.id_mesure = m.id LEFT JOIN filtre as f ON f.id = fm.id_filtre LEFT JOIN uri as u ON u.id_mesure = m.id WHERE c.uri = :uri GROUP BY m.id');
		$query->execute(array(':uri' => $category));

		// Retourne faux dans le cas ou aucun résultat n'est trouvé
		if (!$query->rowCount()) { return false; }

		$record = '';
		foreach ($query->fetchAll(PDO::FETCH_OBJ) as $object) {

			// Filtres
			if ($object->filtre) {
				$filtres = explode(',', $object->filtre);
				if ($filtres) {
					$filtre = [];
					foreach ($filtres as $key => $value) {
						$filtre[] = $object->filtre = explode(':', $value);
					}
					$object->filtre = $filtre;
				} else {
					$object->filtre = explode(':', $object->filtre);
				}
			}

			// Variables
			if (strpos($object->formule, '?') !== false) {
				$frameArray = explode('?', $object->formule);
				foreach ($frameArray as $key => $value) {
					if ($key % 2) {
						$value = strtolower($value);
						if (!isset($_SESSION[$object->id .'Obj'][$key])) {
							$_SESSION[$object->id .'Obj'][$key] = 1;
						}
						$object->variable[$key] = (isset($_GET[$value]) && is_numeric($_GET[$value])) ? $_GET[$value] : $_SESSION[$object->id .'Obj'][$key];
					}
				}
				if (count($_GET) > 1) {
					$_SESSION[$object->id .'Obj'] = $object->variable;
				}
			}

			$record[] = new $this->entity($object);
		}

		return $record;
	}

	public function getIdByUri($mesure) {
		$query = $this->db->prepare('SELECT m.id FROM mesure as m LEFT JOIN uri as u ON u.id_mesure = m.id WHERE uri = :uri LIMIT 1 ');
		$query->execute(array(':uri' => $mesure));

		if (!$query->rowCount()) { return false; }

		return $query->fetch()[0];
	}

	// Fonction de calcul de chaque mesure
	public function calculValue($category, $mesures, $idMesure = null) {

		$allow = ($category == 'cryptographie') ? false : true;

		// Récupération de la valeur
		$value = isset($_GET['value']) ? $_GET['value'] : 1;

		if (!is_numeric($value) && $allow) { $value = 1; }

		if ($idMesure) {
			foreach ($mesures as $key => $mesure) {
				if ($mesure->id == $idMesure) {
					$object = $mesure;
				}
			}
		}

		// Récupèration de la référence associée à la catégorie
		$query = $this->db->prepare('SELECT reference FROM categorie WHERE uri = :uri LIMIT 1');
		$query->execute(array(':uri' => $category));

		if (!$query->rowCount()) { return false; }

		$row = $query->fetch();
		$reference = $row['reference'];
		
		// Calcul de la référence avec la formule inverse
		$frame = ($idMesure) ? $object->formule_inverse : $mesures{0}->formule_inverse;

		$frame = str_replace($reference, $value, $frame);
		
		if (strpos($frame, '?') !== false) {
			$frame = $this->replaceVariable($frame, $object);
		}

		if (strpos($frame, '!') !== false) {
			$frame = $this->replaceFunction($frame);
		}

		if ($allow) {
			$frame = str_replace('--', '+', $frame);
			eval('$value = '. $frame .';');
		} else {
			$value = $frame;
		}

		// Remplacement de la référence dans chaque mesure
		foreach ($mesures as $mesure) {
			$frame = str_replace($reference, $value, $mesure->formule);

			// Identification d'une variable
			if (strpos($frame, '?') !== false) {
				$frame = $this->replaceVariable($frame, $mesure);
			}

			if (strpos($frame, '!') !== false) {
				$frame = $this->replaceFunction($frame);
			}

			if ($allow) {
				// Dans le cas ou 2 "-" se suivent dans une formule, ils sont remplacés par un "+"
				$frame = str_replace('--', '+', $frame);
				eval('$result = '. $frame .';');

				// On garde le résultat de la formule en variable
				$resultWithoutRound = $result;

				// Arrondi au nombre significatif souhaité
				$result = round($result, $_SESSION['sNumber']);

				$resultNearZero = false;
				if (preg_match('/(^0,[0]{'. $_SESSION['sNumber'] .',}$)|(E-\d*$)/', $resultWithoutRound)) {
					$resultNearZero = true;
				}

				if (strrpos($result, '+') == false && strrpos($result, '-') == false) {
					$result = number_format($result, $_SESSION['sNumber'], ',', ' ');
					$result = rtrim(rtrim($result, 0), ',');
					$result = ($resultNearZero) ? '≈ '. $result : $result; 
				}

			} else {
				$result = $frame;
			}

			$mesure->value = $result;
		}

		// Ajoute une conversion supplémentaire au statistique
		$this->incrementCounter();
	}

	// Remplace les variables dans une formule
	public function replaceVariable($frame, $mesure) {
		
		$frameArray = explode('?', $frame);

		foreach ($_SESSION[$mesure->id .'Obj'] as $key => $value) {
			$frameArray[$key] = $value;
		}

		$frame = implode('', $frameArray);
		return $frame;
	}

	// Execute une fonction dans une formule
	// hash = fonction PHP, hashcat = hashcat Program
	public function replaceFunction($frame) {
		$frameArray = explode('!', $frame);
		$param = substr($frameArray[1], 1, -1);

		if ($frameArray[0] == 'hash') {

			

		} else if ($frameArray[0] == 'hashcat') {

			$this->startCrypto($param);
			return false;

		} else {

			include 'vendor/algo/'. $frameArray[0] .'.php';

		}

		if (strpos($param, '|') !== false) {
			$param = explode('|', $param);
			$result = call_user_func_array($frameArray[0], $param);
		} else {
			$result = call_user_func($frameArray[0], $param);
		}
		return $result;
	}

	public function incrementCounter() {

		$path = RP .'public'. DS .'js'. DS .'conversion.json';

		$jsonFile = file_get_contents($path);
		$nbConversion = json_decode($jsonFile);

		$data = [
			"count" => ++$nbConversion->count
		];

		file_put_contents($path, json_encode($data), LOCK_EX);
	}

	public function checkUserApi($data) {

		$email = (isset($data['email'])) ? $data['email'] : 0;
		$password = (isset($data['password'])) ? $data['password'] : 0;
		$list  = (isset($data['list'])) ? unserialize(base64_decode($data['list'])) : 0;
		$config = (isset($data['config'])) ? unserialize(base64_decode($data['config'])) : 0;

		$brand = APP_NAME .' : ';

		// Controle des paramètre GET

		if (!$email) {
			echo $brand .'Paramètre email non trouvé';
			exit;
		}

		if (!$password) {
			echo $brand .'Paramètre password non trouvé';
			exit;
		}

		if (!$list) {
			echo $brand .'Paramètre list non trouvé';
			exit;
		}

		if (!is_array($list)) {
			echo $brand .'Paramètre list n\'est pas un tableau';
			exit;
		}

		if (!array_filter($list, 'is_int')) {
			echo $brand .'Votre liste ne doit contenir que des ID de mesure';
			exit;
		}

		if (count($list) < 2) {
			echo $brand .'Votre liste doit contenir au minimum deux ID';
			exit;
		}

		$query = $this->db->prepare('SELECT id, login, nom, prenom, admin FROM utilisateur WHERE login = :email AND password = :password LIMIT 1');
		$query->execute(array(':email' => addslashes($email), ':password' => md5($password)));

		if ($query->rowCount() != 1) {
			echo $brand .'Identifiants incorrects';
			exit;
		}

		$sql = '';
		foreach ($list as $key => $value) {
			$operator = ' OR';
			if (!$key) { $operator = ' AND'; }
			$sql .=  $operator .' id = '. $value;
		}

		// Récupère toutes les mesures
		$query = $this->db->prepare('SELECT * FROM mesure WHERE actif = 1'. $sql);
		$query->execute();

		if (!$query->rowCount()) { echo 'Aucune mesure trouvée. Vérifier les ID de la liste'; exit; }

		// Hauteur du tableau de conversion
		$height = ($config['size']['height'] != '') ? $config['size']['height'].'px' : '300px';
		// Largeur du tableau de conversion
		$width  = ($config['size']['width'] != '') ? $config['size']['width'].'px' : 'auto';

		$lineSize  = ($config['line_size'] != '') ? $config['line_size'].'px' : 40;

		$i = 0;
		$frame  = '<div id="ca_container" style="width: '. $width .'; height: '. $height .'; overflow-x: hidden; overflow-y: auto;">';
		
		while ($row = $query->fetch()) {

			$striped = '';
			if ($i % 2 && $config['striped']) { $striped = 'striped'; }
			$i++;

			$frame .= '<div class="ca_line '. $striped .'" id="'. $row['id'] .'" style="height: '. $lineSize .'">';
			$frame .= '<div class="ca_keyConvert">';

			$frame .= '<span class="ca_unit">'. $row['unite'] .'</span>';

			$frame .= '</div>';
			$frame .= '<div class="ca_valueConvert" style="line-height: '. $lineSize .'">';

			$frame .= '<input type="text" class="'. $striped .'" style="height: '. ($lineSize-2) .'" value="1">';
			$frame .= '<span class="abrege">'. $row['abrege'] .'</span>';

			$frame .= '</div>';
			$frame .= '</div>';

		}
		if ($config['credit']) {
			$frame .= '<a href="http://'. APP_NAME .'.net">'. APP_NAME .'.net</a>';
		}
		$frame .= '</div>';

		return $frame;
	}

	public function getMesureForApi($data) {
		// Récupération de la formule de la référence selon la catégorie
		$query = $this->db->prepare('SELECT formule, formule_inverse FROM mesure WHERE id = :id LIMIT 1');
		$query->execute(array(':id' => $data['id']));

		if ($query->rowCount() != 1) { return false; }
		
		$row = $query->fetch();

		$reference = $this->getReference($row['formule']);

		$frame = $row['formule_inverse'];
		$frame = str_replace($reference, $data['number'], $frame);

		eval('$value = '. $frame .';');

		$sql = '';
		foreach ($data['list'] as $key => $v) {
			$operator = ' OR';
			if (!$key) { $operator = ' AND'; }
			$sql .=  $operator .' id = '. $v;
		}

		// Récupère toutes les mesures selon la liste
		$query = $this->db->prepare('SELECT * FROM mesure WHERE actif = 1'. $sql);
		$query->execute();

		if (!$query->rowCount()) { return false; }

		// Construction du tableau de mesure
		while($row = $query->fetch()) {
			$frame = $row['formule'];

			// Remplace toutes les occurrences dans une chaîne (cherché, remplacé par, sujet)
			$frame = str_replace($reference, $value, $frame);
			
			eval('$result = '.$frame.';');

			if (strrpos($result, '+') == false && strrpos($result, '-') == false) {
				$result = number_format($result, 4, ',', ' ');
				$result = rtrim(rtrim($result, 0), ',');
			}

			$category[] = $result;
		}

		return $category;
	}

	// Retrouve la reference en fonction de la formule
	public function getReference($frame) {
		$query = $this->db->prepare('SELECT reference FROM categorie');
		$query->execute();

		while ($row = $query->fetch()) {
			if (preg_match('/'. $row['reference'] .'/', $frame)) {
				return $row['reference'];
			}
		}
		return false;
	}

	public function startCrypto($param) {

		if (strpos($param, '|') !== false) {
			$param = explode('|', $param);
		}

		$str = $param[1];

		// Configuration Hashcat
		$config = '';
		$config .= ' --workload-profile=1'; // Niveau de chargement
		$config .= ' --runtime=30'; // Fin de du processus dans 30s
		$config .= ' --status';
		$config .= ' --status-timer=3'; // Log toute les 3 secondes
		//$config .= ' --status-automat';
		$config .= ' --force'; // Supprime les warnings
		//$config .= ' --outfile=hashcat/hash_'. $hashtype .'.pot';
		$config .= ' --session=hashcat/'. $_SESSION['id']; // Associé à une session
		$config .= ' --hash-type='. $param[0]; // Hash souhaité
		$config .= ' --attack-mode=3 '; // Brutal force

		$globalPot = 'vendor/hashcat/hash_'. $param[0] .'.pot';
		// Dans le cas ou le POT global n'existe pas
		if (!file_exists($globalPot)) { fclose(fopen($globalPot, 'w')); }

		// Renommage du fichier POT pour l'associer à la session courante
		rename($globalPot, 'vendor/hashcat/'. $_SESSION['id'] .'.pot');

		$hashcatPath = '/home/yves/Téléchargements/cudaHashcat-2.01/cudaHashcat64.bin';

		$cmd = 'nohup '. $hashcatPath . $config . $str .' > /dev/null 2>vendor/hashcat/status.log &';
		var_dump($cmd);
		exit;

		// Permet de lancer la commande de façon assynchrone
		pclose(popen($cmd, 'r'));

		$query = $this->db->prepare('INSERT INTO cryptographie VALUES (:id, :session, null, null, 1)');
		$query->execute(array(':id' => 0, ':session' => $_SESSION['id']));

		return true;
	}

	// Retourne le tritre de la mesure en fonction de l'uri
	public function getTitleByUri($mesure) {
		$query = $this->db->prepare('SELECT m.unite FROM mesure as m LEFT JOIN uri as u ON u.id_mesure = m.id WHERE uri = :uri LIMIT 1 ');
		$query->execute(array(':uri' => $mesure));

		if (!$query->rowCount()) { return false; }

		return $query->fetch()[0];
	}

	// Retourne le tableau des uris pour les mesures
	public function getUriMesure() {
		$query = $this->db->prepare('SELECT u.uri as mesure, c.uri as categorie FROM uri as u LEFT JOIN mesure as m ON u.id_mesure = m.id LEFT JOIN categorie as c ON c.id = m.categorie');
		$query->execute();

		if (!$query->rowCount()) { return false; }

		$record = '';
		while ($row = $query->fetch()) {
			$record[] = $row['categorie'] .'/'. $row['mesure'];
		}

		return $record;
	}

	public function getFilterByCategory($category) {
		$query = $this->db->prepare('SELECT DISTINCT f.libelle, f.nature FROM `filtre/mesure` as fm LEFT JOIN filtre as f ON f.id = fm.id_filtre LEFT JOIN mesure as m ON m.id = fm.id_mesure LEFT JOIN categorie as c ON c.id = m.categorie WHERE c.uri = :category ORDER BY f.nature, f.libelle');
		$query->execute(array(':category' => $category));

		if (!$query->rowCount()) { return false; }

		$filtres = '';
		while ($row = $query->fetch()) {
			$filtres[] = array('libelle' => $row['libelle'], 'nature' => $row['nature']);
		}

		return $filtres;
	}
}

?>