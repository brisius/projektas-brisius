<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();

$elementCount = $dalysObj->irasu_kiekis();


include 'utils/paging.php';

$paging = new paging(9);

$paging->process($elementCount, $pageId);

$data = $dalysObj->perziureti_dalis($paging->size, $paging->first);



//Užkrauname registracijos langą
include 'templates/daliu_perziura.html';
?>

