<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/dalys.php');
$dalysObj = new dalys();
$data = $dalysObj->gauti_sutartis($contract);
include ('templates/daliu_uzsakymu_langas.html');
?>