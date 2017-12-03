<?php

class remontai {
	public $remontai_lentele = '';
	public $paslaugos_lentele = '';
	public $miestai_lentele = '';
	public $parduotuves_lentele = '';


	public function __construct(){
		$this->remontai_lentele = 'Remontai';
		$this->paslaugos_lentele = 'Remonto_paslaugos';
		$this->miestai_lentele = 'Miestai';
		$this->parduotuves_lentele = 'Parduotuves';
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
					FROM {$this->remontai_lentele}";
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


	public function gautiParduotuves($limit = null, $offset = null){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT *
					FROM {$this->parduotuves_lentele}{$limitOffsetString}";
		$data = mysql::select($query);
		
		return $data;
	}
		public function gautiMiestus($limit = null, $offset = null){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT *
					FROM {$this->miestai_lentele}{$limitOffsetString}";
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
		$query = "INSERT INTO {$this->remontai_lentele}
				(
					pristatymo_vieta,
					gamintojas,
					modelis,
					kompiuterio_tipas,
					gedimo_informacija,
					pristatymo_data_laikas,
					fk_Parduotuve
				)
				VALUES
				(
					'{$data['place']}',
					'{$data['brand']}',
					'{$data['model']}',
					'{$data['computer_type']}',
					'{$data['malfunction_info']}',
					'{$data['date']}',
					'{$data['shop']}'
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