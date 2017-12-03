<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();

$_SESSION['part_id'] = $id;
$data = $dalysObj->gauti_dali($id);


//Užkrauname registracijos langą
include 'templates/daliu_informacija.html';
?>
