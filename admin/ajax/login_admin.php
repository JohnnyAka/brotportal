<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();
$result = $db->getData("settings", array('adminName'), "adminName='".$_POST["adminName"]."' AND adminPassword='".$_POST["adminPassword"]."'");

if ($result != false){
	$_SESSION['trustedUser'] = "true";
	echo true;
}
else{
	echo false;
}
?>

