<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/remontai.php');
$remontaiObj = new remontai();
$data = $remontaiObj->gauti_sutartis($contract);
include ('templates/remonto_perziura.html');
?>