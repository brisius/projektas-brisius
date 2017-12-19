<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/kompiuteriai.php');
$dalysObj = new kompiuteriai();

//privalomi laukai
$required = array(); 
$validations = array();
$maxLengths = array();
$errors = array();

$data = array();
//neteisingai įvestiems laukams
$formErrors = null;
if(!empty($_POST['createpart'])){

	include 'utils/validator.php';
	$validator = new validator($validations, $required, $maxLengths);

	if($validator->validate($_POST)) {
		// suformuojame laukų reikšmių masyvą SQL užklausai
		$dataPrepared = $validator->preparePostFieldsForSQL();
		//tikriname ar teisingas slaptažodis ir ar nėra jau tokio vartotojo
		//$errors = $klientaiObj->patikrinti_duomenis($dataPrepared);
		$dalysObj->irasyti($dataPrepared);
		header("Location: index.php?module=kompiuteriu_pirkimas");
		exit;

	} else {
		// gauname klaidų pranešimą
		$formErrors = $validator->getErrorHTML();
		// gauname įvestus laukuss
		$data = $_POST;
	}
}
if(!empty($_POST['editpart'])){

	include 'utils/validator.php';
	$validator = new validator($validations, $required, $maxLengths);

	if($validator->validate($_POST)) {
		// suformuojame laukų reikšmių masyvą SQL užklausai
		$dataPrepared = $validator->preparePostFieldsForSQL();
		//tikriname ar teisingas slaptažodis ir ar nėra jau tokio vartotojo
		//$errors = $klientaiObj->patikrinti_duomenis($dataPrepared);
		$dalysObj->pakeisti_duomenis($dataPrepared, $_SESSION['part_id']);
		header("Location: index.php?module=kompiuteriu_pirkimas");
		exit;

	} else {
		// gauname klaidų pranešimą
		$formErrors = $validator->getErrorHTML();
		// gauname įvestus laukus
		$data = $_POST;
	}
}
//jei paspaustas dalių kūrimo mygtukas
if($action == "create"){
	include 'templates/kompiuteriu_kurimo_langas.html';
} 
//jei paspaustas dalių šalinimo mygtukas
if($action == "delete"){
	$dalysObj->trinti_kompiuteri($_SESSION['part_id']);
	header("Location: index.php?module=kompiuteriu_pirkimas");
	exit;
}
//jei paspaustas dalių informacijos kaitimo mygtukas
if($action == "edit"){
	$data = $dalysObj->gauti_kompiuteri($_SESSION['part_id']);
	include 'templates/kompiuteriu_kurimo_langas.html';
}


?>