<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
var_dump($_SESSION['cart']); die();
?>