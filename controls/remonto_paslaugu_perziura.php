<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/remontai.php');
$remontaiObj = new remontai();

$elementCount = $remontaiObj->irasu_kiekis();

include 'utils/paging.php';

$paging = new paging(9);

$paging->process($elementCount, $pageId);

$data = $remontaiObj->perziureti_paslaugas($paging->size, $paging->first);



//Užkrauname registracijos langą
include 'templates/remonto_paslaugu_perziura.html';
?>

