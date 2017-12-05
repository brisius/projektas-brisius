<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('lib/remontai.php');
$remontaiObj = new remontai();
$data = $remontaiObj->trinti_remonta($id);
include ('templates/remonto_perziura.html');
?>