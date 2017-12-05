<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();
if($action == "partorders"){
	$data = $dalysObj->gauti_sutartis_ataskaitai($contract);
}
else if($action == "view"){
	$data = $dalysObj->gauti_dalis_pagal_sutarti($id);
}
include ('templates/kompiuterio_daliu_ataskaitos_langas.html');
?>