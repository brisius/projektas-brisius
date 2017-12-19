<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/kompiuteriai.php');
$dalysObj = new kompiuteriai();

$elementCount = $dalysObj->filtruotu_irasu_kiekis($category);

include 'utils/paging.php';

$paging = new paging(9);

$paging->process($elementCount, $pageId);
if(isset($_POST['pavadinimas'])){
	$selectedBrands = $_POST['pavadinimas'];
	$data = $dalysObj->pasirinkti_filtrus($category, $selectedBrands);
	$brands = $dalysObj->gamintojai($category);
}
else{
	$selectedBrands = 'false';
	$data = $dalysObj->pasirinkti_filtrus($category, $selectedBrands);
	$brands = $dalysObj->gamintojai($category);
}
include 'templates/kompiuteriu_pirkimas.html';
?>

