<?php

class remontai {
	public $dalys_lentele = '';
	public $paslaugos_lentele = '';


	public function __construct(){
		$this->dalys_lentele = 'Dalys';
		$this->paslaugos_lentele = 'Remonto_paslaugos';
	}

	public function perziureti_paslaugas($limit = null, $offset = null){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT *
					FROM {$this->paslaugos_lentele}{$limitOffsetString}";
		$data = mysql::select($query);
		
		return $data;
	}

	public function irasu_kiekis(){
		$query = "SELECT COUNT(`id`) as `kiekis`
					FROM {$this->dalys_lentele}";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}

	public function gauti_dali($id){
		$query = "SELECT * 
					FROM {$this->dalys_lentele}
					LEFT JOIN {$this->daliu_tipai_lentele}
					ON {$this->dalys_lentele}.dalies_tipas = {$this->daliu_tipai_lentele}.id_dalies_tipas
					WHERE id = '{$id}' ";
		$data = mysql::select($query);
		return $data;
	}


	public function gautiTipus($limit = null, $offset = null){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT *
					FROM {$this->daliu_tipai_lentele}{$limitOffsetString}";
		$data = mysql::select($query);
		
		return $data;
	}

	public function pakeisti_duomenis($data, $id){
		$query = "UPDATE {$this->dalys_lentele}
					SET gamintojas='{$data['brand']}',
						aprasymas='{$data['description']}',
						svoris='{$data['weigth']}',
						pagaminimo_data='{$data['date']}',
						kiekis='{$data['amount']}',
						garantijos_laikotarpis='{$data['warranty']}',
						pristatymo_laikas='{$data['delivery']}',
						kaina='{$data['price']}',
						dalies_tipas='{$data['type']}'
						WHERE id='{$id}';
		";
		mysql::query($query);
	}

	public function irasyti($data){
		$query = "INSERT INTO {$this->dalys_lentele}
				(
					gamintojas,
					aprasymas,
					svoris,
					pagaminimo_data,
					kiekis,
					garantijos_laikotarpis,
					pristatymo_laikas,
					kaina,
					dalies_tipas
				)
				VALUES
				(
					'{$data['brand']}',
					'{$data['description']}',
					'{$data['weigth']}',
					'{$data['date']}',
					'{$data['amount']}',
					'{$data['warranty']}',
					'{$data['delivery']}',
					'{$data['price']}',
					'{$data['type']}'
				)";
		mysql::query($query);
	}

	public function trinti_dali($id){
		$query = "DELETE FROM {$this->dalys_lentele}
					WHERE `id`='{$id}'";
		mysql::query($query);
	}


}

?>