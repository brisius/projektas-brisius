<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/kompiuteriai.php');
$dalysObj = new kompiuteriai();
$data = $dalysObj->gauti_sutartis($contract);
include ('templates/kompiuteriu_uzsakymu_langas.html');
?>