<?php

class klientai {
	private $klientai_lentele = '';
	private $vartotojai_lentele = '';
	private $miestu_lentele = '';

	public function __construct(){
		$this->klientai_lentele = 'Klientai';
		$this->vartotojai_lentele = 'vartotojai';
		$this->miestu_lentele = 'Miestai';
	}

	public function patikrinti_duomenis($data){
		$query = "SELECT* FROM {$this->vartotojai_lentele} WHERE username='{$data['usernamesignup']}'";
		$result = mysql::select($query);
		$error = array();
		//patiktini ar slaptažodžiai sutampa
		if(!empty($result)){
			$error[0] = "Username already exists!";
		}
		if($data['passwordsignup'] != $data['passwordsignup_confirm']){
			$error[1] = "Password do not match!";
		}
		return $error;
	}

	public function patikrinti_prisijungimo_duomenis($data){
		$query = "SELECT* FROM {$this->vartotojai_lentele} WHERE username='{$data['username']}'";
		$result = mysql::select($query);
		$error = array();
		if(empty($result)){
			$error[0] = "Username do not exist! Please register";
		}
		elseif(!empty($result)){
			if($result[0]['password'] != $hashedPass = substr(hash('sha256', $data['password']), 5, 32)){
				$error[1] = "Password does not match!";
			}
		}
		return $error;
	}

	public function irasyti($data){
		$hashedPass = substr(hash('sha256', $data['passwordsignup']), 5, 32);

		$queryUsers = "INSERT INTO {$this->vartotojai_lentele}
		(
			username,
			password,
			userlevel
		)
		VALUES
		(
			'{$data['usernamesignup']}',
			'{$hashedPass}',
			'1'

		)";

		$queryCustomers = "INSERT INTO {$this->klientai_lentele}
		(
			vardas, 
			pavarde,
			el_pastas,
			lytis,
			telefonas,
			adresas,
			sukurimo_data,
			gimimo_data,
			fk_Kliento_grupe,
			fk_Miestas
		)
		VALUES
		(
			'{$data['namesignup']}',
			'{$data['surnamesignup']}',
			'{$data['emailsignup']}',
			'{$data['gendersignup']}',
			'{$data['telephonesignup']}',
			'{$data['addresssignup']}',
			'NOW()',
			'{$data['userbirth']}',
			'0',
			'{$data['city']}'


		)";
		mysql::query($queryUsers);
		mysql::query($queryCustomers);
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
					FROM {$this->miestu_lentele}{$limitOffsetString}";
		$data = mysql::select($query);
		
		return $data;
	}
}

?>