<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

class kompiuteriai {
	
	public $dalys_lentele = '';
	public $daliu_tipai_lentele = '';

	private $klientai_lentele = '';
	private $vartotojai_lentele = '';
	private $miestu_lentele = '';

	private $mokejimu_lentele = '';
	private $sutarciu_lentele = '';

	private $visos_dalys_lentele = '';
	
	// Tado Lentelės
	private $kompiuteriai_lentele = '';
	
	public function __construct(){
		$this->dalys_lentele = 'Dalys';
		$this->daliu_tipai_lentele = 'dalies_tipai';
		$this->klientai_lentele = 'Klientai';
		$this->vartotojai_lentele = 'vartotojai';
		$this->miestu_lentele = 'Miestai';
		$this->mokejimu_lentele= 'Mokejimai';
		$this->sutarciu_lentele = 'Sutartys';
		$this->visos_dalys_lentele = 'visos_dalys';
		$this->kompiuteriai_lentele = 'Kompiuteriai';
	}
	
		public function gauti_dalis_pagal_sutarti($id){
		$query = "SELECT *
					FROM {$this->visos_dalys_lentele} 
					LEFT JOIN {$this->dalys_lentele}
					ON {$this->dalys_lentele}.id = {$this->visos_dalys_lentele}.fk_Dalis
					WHERE fk_Sutartis='{$id}'";
		$data = mysql::select($query);
		return $data;
	}
	
	public function gauti_sutartis_ataskaitai($filter){
		$data2;
		if($filter == ''){
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Remontas='1'";
			$data2 = mysql::select($query);
		}else{
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE sutarties_busena='{$filter}' AND fk_Remontas='1'";
			$data2 = mysql::select($query);
		}
		
		return $data2;
	}
	
	public function gauti_sutartis($filter){
		$query = "SELECT id
					FROM {$this->klientai_lentele} WHERE fk_Vartotojas='{$_SESSION['userid']}'";
		$data = mysql::select($query);

		$data2;
		if($filter == ''){
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Klientas='{$data[0]['id']}' AND fk_Remontas='1'";
			$data2 = mysql::select($query);
		}else{
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Klientas='{$data[0]['id']}' AND sutarties_busena='{$filter}' AND fk_Remontas='1'";
			$data2 = mysql::select($query);
		}
		
		return $data2;
	}

	public function irasyti_pirkimus(){
		$pay = '';
		$state = '';
		if($_SESSION['payment'] == "Bank transfer"){
			$pay = 1;
			$state = 2;
		}
		if($_SESSION['payment'] == "In the shop"){
			$pay = 2;
			$state = 0;
		}

		$queryPayment = "INSERT INTO {$this->mokejimu_lentele}
				(
					data,
					suma,
					fk_Mokejimo_tipas,
					fk_Mokejimo_busena
				)
				VALUES
				(
					 NOW(),
					'{$_SESSION['totalPrice']}',
					'{$pay}',
					'{$state}'
				)";
		mysql::query($queryPayment);

		$query = "SELECT id
					FROM {$this->klientai_lentele} WHERE fk_Vartotojas='{$_SESSION['userid']}'";
		$data = mysql::select($query);

		$queryContract = "INSERT INTO {$this->sutarciu_lentele}
				(
					sutarties_data,
					kaina,
					pristatymas,
					sutarties_busena,
					fk_Klientas,
					fk_Remontas,
					fk_Mokejimas,
					fk_Darbuotojas
				)
				VALUES
				(
					 NOW(),
					'{$_SESSION['totalPrice']}',
					'{$_SESSION['deliveryOption']}',
					'1',
					'{$data[0]['id']}',
					'1',
					'4',
					'1'
				)";

		mysql::query($queryContract);

		$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Klientas='{$data[0]['id']}'";
		$data2 = mysql::select($query);
		$count = count($data2);
		$contractId = $data2[$count-1]['nr'];

		$queryAllParts = "INSERT INTO {$this->visos_dalys_lentele} (fk_Sutartis, fk_Dalis, nupirkta) 
		VALUES ";
		$counter = 1;
		foreach ($_SESSION['cart'] as $value) {
			if($value != null){
				$queryAllParts = $queryAllParts . "({$contractId}, {$value['id']}, {$_SESSION['quantity'][$counter]}),";
			}
			$counter++;
		}
		$queryAllParts = substr($queryAllParts, 0, -1);
		$queryAllParts = $queryAllParts . ";";
		mysql::query($queryAllParts);
	}

	public function gauti_kliento_duomenis(){

		$query = "SELECT *
					FROM {$this->klientai_lentele} WHERE fk_Vartotojas='{$_SESSION['userid']}'";
		$data = mysql::select($query);
		return $data;
	}

	public function gauti_miesta($cityId){
		$query = "SELECT pavadinimas
					FROM {$this->miestu_lentele} WHERE miesto_kodas='{$cityId}'";
		$data = mysql::select($query);
		return $data;
	}

	public function pasirinkti_filtrus($filter, $selectedBrands){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		$data;
		if($selectedBrands != 'false'){
			$brandsText = '';
			foreach ($selectedBrands as $brand) {
				$brandsText = $brandsText . "'$brand',";
			}
			$query = "SELECT *
			FROM {$this->kompiuteriai_lentele}{$limitOffsetString}
			WHERE pavadinimas='{$filter}'";
			$query = substr($query, 0, -1);
			$query = $query . ");";
			$data = mysql::select($query);
		}
		else{
			$query = "SELECT *
					FROM {$this->kompiuteriai_lentele}{$limitOffsetString}
					WHERE pavadinimas='{$filter}'";
			$data = mysql::select($query);
		}
		
		return $data;
	}

	public function gamintojai($filter){
		$query = "SELECT DISTINCT gamintojas
					FROM {$this->kompiuteriai_lentele} WHERE pavadinimas='{$filter}'";
		$data = mysql::select($query);
		return $data;
	}
	//"SELECT COUNT(`id`) as `pavadinimas` 
	public function filtruotu_irasu_kiekis($filter){
		$query = "SELECT COUNT(`pavadinimas`) as `pavadinimas` 
					FROM {$this->kompiuteriai_lentele}
					WHERE pavadinimas = '{$filter}'";
		$data = mysql::select($query);
		
		return $data[0]['pavadinimas'];
	}

	public function perziureti_kompiuterius($limit = null, $offset = null){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT *
					FROM {$this->kompiuteriai_lentele}{$limitOffsetString}";
		$data = mysql::select($query);
		
		return $data;
	}
	

	public function irasu_kiekis(){
		$query = "SELECT COUNT(`id`) as `kiekis`
					FROM {$this->dalys_lentele}";
		$data = mysql::select($query);
		
		return $data[0]['kiekis'];
	}
	
	
	
	public function gauti_kompiuteri($id){
		$query = "SELECT * 
					FROM {$this->kompiuteriai_lentele}
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
		$query = "UPDATE {$this->kompiuteriai_lentele}
					SET pavadinimas='{$data['pavadinimas']}',
						procesorius='{$data['procesorius']}',
						vaizdo_plokste='{$data['vaizdo_plokste']}',
						motinine_plokste='{$data['motinine_plokste']}',
						operatyvioji_atmintis='{$data['operatyvioji_atmintis']}',
						disko_talpa='{$data['disko_talpa']}',
						ekrano_istrizaine='{$data['ekrano_istrizaine']}',
						operacine_sistema='{$data['operacine_sistema']}',
						pagaminimo_data='{$data['pagaminimo_data']}',
						svoris='{$data['svoris']}',
						baterijos_talpa='{$data['baterijos_talpa']}',
						jungiamumas='{$data['jungiamumas']}',
						kitos_savybes='{$data['kitos_savybes']}',
						garantijos_laikotarpis='{$data['garantijos_laikotarpis']}',
						pristatymo_laikas='{$data['pristatymo_laikas']}',
						kaina='{$data['kaina']}'
						WHERE id='{$id}';
		";
		mysql::query($query);
	}

	public function irasyti($data){
		$query = "INSERT INTO {$this->kompiuteriai_lentele}
				(	
					pavadinimas,
					procesorius,
					vaizdo_plokste,
					motinine_plokste,
					operatyvioji_atmintis,
					disko_talpa,
					ekrano_istrizaine,
					operacine_sistema,
					pagaminimo_data,
					svoris,
					baterijos_talpa,
					jungiamumas,
					kitos_savybes,
					garantijos_laikotarpis,
					pristatymo_laikas,
					kaina		
					
				)
				VALUES
				(
					'{$data['pavadinimas']}',
					'{$data['procesorius']}',
					'{$data['vaizdo_plokste']}',
					'{$data['motinine_plokste']}',
					'{$data['operatyvioji_atmintis']}',
					'{$data['disko_talpa']}',
					'{$data['ekrano_istrizaine']}',
					'{$data['operacine_sistema']}',
					'{$data['pagaminimo_data']}',
					'{$data['svoris']}',
					'{$data['baterijos_talpa']}',
					'{$data['jungiamumas']}',
					'{$data['kitos_savybes']}',
					'{$data['garantijos_laikotarpis']}',
					'{$data['pristatymo_laikas']}',
					'{$data['kaina']}'
				)";
		//var_dump($query); die();
		mysql::query($query);
	}

	public function trinti_kompiuteri($id){
		$query = "DELETE FROM {$this->kompiuteriai_lentele}
					WHERE `id`='{$id}'";
		mysql::query($query);
	}
	
	
}

?>