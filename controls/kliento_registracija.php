<?php

include ('lib/klientai.php');

$klientaiObj = new klientai();

//privalomi laukai
$required = array(); 
$validations = array();
$maxLengths = array();
$errors = array();

$data = array();
//neteisingai įvestiems laukams
$formErrors = null;

if(!empty($_POST['signup'])){


	include 'utils/validator.php';
	$validator = new validator($validations, $required, $maxLengths);

	if($validator->validate($_POST)) {
		// suformuojame laukų reikšmių masyvą SQL užklausai
		$dataPrepared = $validator->preparePostFieldsForSQL();
		//tikriname ar teisingas slaptažodis ir ar nėra jau tokio vartotojo
		$errors = $klientaiObj->patikrinti_duomenis($dataPrepared);
		if(empty($errors)){
			//išsaugome vartotoją
			$klientaiObj->irasyti($dataPrepared);
			//atidarome prisijungimo langą
			include 'templates/prisijungimo_langas.html';
		} else {

		}

	} else {
		// gauname klaidų pranešimą
		$formErrors = $validator->getErrorHTML();
		// gauname įvestus laukus
		$data = $_POST;
	}
}


//Užkrauname registracijos langą
include 'templates/registracijos_langas.html';
?>

