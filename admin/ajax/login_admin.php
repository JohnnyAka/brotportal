<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();

$_SESSION['trustedUser'] = "false";

$result = $db->getData("settings", array('adminName','adminPassword'), "adminName=?1", array($_POST["adminName"]));

$passwordCorrect = false;
if(!empty($result)) {
    $passwordCorrect = password_verify($_POST["adminPassword"], $result[0]['adminPassword']);
}

if ($passwordCorrect === true){
	$_SESSION['trustedUser'] = "true";
	echo true;
}
else{
	echo false;
}
?>

