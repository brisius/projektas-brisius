<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();

if($action == "delete"){
	$_SESSION['cart'][$id] = null;
	$_SESSION['counterF'] = $_SESSION['counterF'] - 1;
	$_SESSION['quantity'][$id] = 0;
	include ('templates/pirkimo_langas.html');
}
else if($action == "cart"){
	include ('templates/pirkimo_langas.html');
}
else if($action == "address"){

	$data = $dalysObj->gauti_kliento_duomenis();
	$city = $dalysObj->gauti_miesta($data[0]['fk_Miestas']);
	include ('templates/pirkimo_adreso_langas.html');
}
else if($action == "delivery"){
	include ('templates/pirkimo_pristatymo_langas.html');
}
else if($action == "payment"){
	if(!empty($_POST['delSubmit'])){
		$_SESSION['deliveryOption'] = $_POST['delSubmit'];
	}
	include ('templates/pirkimo_mokejimo_langas.html');
}
else if($action == "doneCheckout"){
	if(!empty($_POST['delSubmit'])){
		$_SESSION['payment'] = $_POST['delSubmit'];
		$dalysObj->irasyti_pirkimus();
		for ($i=0; $i < $_SESSION['counterF']; $i++) { 
			$_SESSION['cart'][$i] = null;
			$_SESSION['quantity'][$i] = 0;
		}
		$_SESSION['counterF'] = 0;
		$_SESSION['counter'] = 0;
	}
	header("Location: index.php");
}
else{
	$_SESSION['counter'] = $_SESSION['counter'] + 1;
	$_SESSION['counterF'] = $_SESSION['counterF'] + 1;
	$_SESSION['cart'][$_SESSION['counter']] = $_SESSION['toAdd'];
	$_SESSION['cartAmount'][$_SESSION['counter']] = $_SESSION['toAdd'];
	$_SESSION['quantity'][$_SESSION['counter']] = $_POST['quantity'];
	header("Location: index.php?module=daliu_perziura");
}


?>