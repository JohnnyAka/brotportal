<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();
$result = $db->getData("settings", array('adminName'), "adminName=?1 AND adminPassword=?2", array($_POST["adminName"],$_POST["adminPassword"]));

if ($result != false){
	$_SESSION['trustedUser'] = "true";
	echo true;
}
else{
	echo false;
}
?>

