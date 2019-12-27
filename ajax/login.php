<?php
session_start(); 
include('../db_crud.php');

$db = new db_connection();
$result = $db->getData("users", array('id','name','password','agreedAGBs'), "customerID=?1",$_POST["name"]);


$passwordCorrect = false;
if($result != false){
	$passwordCorrect = password_verify($_POST['password'],$result[0]['password']);
}

$returnObject['pwCorrect'] = $passwordCorrect;
$returnObject['agbsRead'] = false;

if ($passwordCorrect){
	$_SESSION['username'] = $result[0]['name'];
	$_SESSION['userid'] = $result[0]['id'];
	$_SESSION['dataBlockedForDisplay'] = false;
	$_SESSION['agbsRead'] = $result[0]['agreedAGBs'];
	$returnObject['agbsRead'] = $result[0]['agreedAGBs'];
}
echo json_encode($returnObject);
?>

