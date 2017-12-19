<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/kompiuteriai.php');
$dalysObj = new kompiuteriai();

$_SESSION['part_id'] = $id;
$data = $dalysObj->gauti_kompiuteri($id);


//Užkrauname registracijos langą
include 'templates/kompiuteriu_perziura.html';
?>
