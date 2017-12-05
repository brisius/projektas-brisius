<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();

$elementCount = $dalysObj->filtruotu_irasu_kiekis($category);

include 'utils/paging.php';

$paging = new paging(9);

$paging->process($elementCount, $pageId);
if(isset($_POST['brand'])){
	$selectedBrands = $_POST['brand'];
	$data = $dalysObj->pasirinkti_filtrus($category, $selectedBrands);
	$brands = $dalysObj->gamintojai($category);
}
else{
	$selectedBrands = 'false';
	$data = $dalysObj->pasirinkti_filtrus($category, $selectedBrands);
	$brands = $dalysObj->gamintojai($category);
}
include 'templates/daliu_perziura.html';
?>

