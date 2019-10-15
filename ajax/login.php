<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();
$result = $db->getData("users", array('id','name','password'), "customerID=?1",$_POST["name"]);


$passwordCorrect = false;
if($result != false){
	$passwordCorrect = password_verify($_POST['password'],$result[0]['password']);
}



if ($passwordCorrect){
	$_SESSION['username'] = $result[0]['name'];
	$_SESSION['userid'] = $result[0]['id'];
	$_SESSION['dataBlockedForDisplay'] = false;
	echo true;
}
else{
	echo false;
}
?>

