<?php

class remontai {
	public $remontai_lentele = '';
	public $paslaugos_lentele = '';
	public $miestai_lentele = '';
	public $parduotuves_lentele = '';
	private $klientai_lentele = '';
	private $vartotojai_lentele = '';
	private $mokejimu_lentele = '';
	private $sutarciu_lentele = '';

	public function __construct(){
		$this->remontai_lentele = 'Remontai';
		$this->paslaugos_lentele = 'Remonto_paslaugos';
		$this->miestai_lentele = 'Miestai';
		$this->parduotuves_lentele = 'Parduotuves';
				$this->klientai_lentele = 'Klientai';
		$this->vartotojai_lentele = 'vartotojai';

		$this->mokejimu_lentele= 'Mokejimai';
		$this->sutarciu_lentele = 'Sutartys';
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
	public function perziureti_ataskaita($limit = null, $offset = null,$id){
		$limitOffsetString = "";
		if(isset($limit)) {
			$limitOffsetString .= " LIMIT {$limit}";
			
			if(isset($offset)) {
				$limitOffsetString .= " OFFSET {$offset}";
			}	
		}
		
		$query = "SELECT* FROM {$this->sutarciu_lentele}
		 WHERE `nr`='{$id}'";
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

		public function gauti_sutartis($filter){
			if(isset($_SESSION['ulevel'])){
                if($_SESSION['ulevel'] == 1) {
                                 $query = "SELECT id
					FROM {$this->klientai_lentele} WHERE fk_Vartotojas='{$_SESSION['userid']}'";
		$data = mysql::select($query);
		$data2;
		if($filter == ''){
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Klientas='{$data[0]['id']}'AND fk_Remontas != '1'";
			$data2 = mysql::select($query);
		}else{
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE fk_Klientas='{$data[0]['id']}' AND sutarties_busena='{$filter}' AND fk_Remontas != '1'";
			//var_dump($query);die();
			$data2 = mysql::select($query);
		}return $data2;
                             }}
                             if(isset($_SESSION['ulevel'])){
                if($_SESSION['ulevel'] != 1) {
		$data2;
		if($filter == ''){
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE  fk_Remontas != '1'";
			$data2 = mysql::select($query);
		}else{
			$query = "SELECT *
					FROM {$this->sutarciu_lentele} WHERE  sutarties_busena='{$filter}' AND fk_Remontas != '1'";
			//var_dump($query);die();
			$data2 = mysql::select($query);
		}return $data2;
                             }}
		
		
		
	}
public function uzsakyti_remonta($data){
		$pay = '';
		$state = '';
		if($data['payment'] == "Bank transfer"){
			$pay = 0;
			$state = 1;
		}
		if($data['payment'] == "In the shop"){
			$pay = 1;
			$state = 2;
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
					0,
					'{$pay}',
					'{$state}'
				)";
		mysql::query($queryPayment);

		$queryMaintenance = "INSERT INTO {$this->remontai_lentele}
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
		mysql::query($queryMaintenance);
				$queryId = "SELECT MAX(id) as `maxid` FROM {$this->remontai_lentele}";
		$maxIdVal = mysql::select($queryId);

		$query = "SELECT id
					FROM {$this->klientai_lentele} WHERE fk_Vartotojas='{$_SESSION['userid']}'";
		$data1 = mysql::select($query);

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
					0,
					'{$data['delPlace']}',
					'1',
					'{$data1[0]['id']}',
					'{$maxIdVal[0]['maxid']}',
					'4',
					'1'
				)";

		mysql::query($queryContract);	
	}
	public function trinti_remonta($id){
		$sutartis = "SELECT* FROM {$this->sutarciu_lentele}
		 WHERE `nr`='{$id}'";
		$data=mysql::select($sutartis);
		//var_dump($sutartis);die();
        $remontasId = $data[0]['fk_Remontas'];
        $mokejimasId = $data[0]['fk_Mokejimas'];
		$query = "DELETE FROM {$this->sutarciu_lentele}
					WHERE `nr`='{$id}'";
		mysql::query($query);
		$query = "DELETE FROM {$this->sutarciu_lentele}
					WHERE `id`='{$remontasId}'";
		mysql::query($query);
	}


}

?>