<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/kompiuteriai.php');
$dalysObj = new kompiuteriai();

$elementCount = $dalysObj->irasu_kiekis();


include 'utils/paging.php';

$paging = new paging(9);

$paging->process($elementCount, $pageId);

$data = $dalysObj->perziureti_kompiuterius($paging->size, $paging->first);



//Užkrauname registracijos langą
include 'templates/kompiuteriu_pirkimas.html';
?>

