<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();

$_SESSION['trustedUser'] = "false";

$result = $db->getData("settings", array('adminName','adminPassword'), "adminName=?1", array($_POST["adminName"]))[0];

$passwordCorrect = password_verify($_POST["adminPassword"],$result['adminPassword']);

if ($passwordCorrect != false){
	$_SESSION['trustedUser'] = "true";
	echo true;
}
else{
	echo false;
}
?>

