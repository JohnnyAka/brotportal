<?php
session_start(); 
include('../queries/db_queries.php');

$db = new db_connection();
$result = $db->getData("users", array('id','name','password'), "customerID='".$_POST["name"]."'");
if($result != false){
	$result = $result[0];
}

if ($result != false AND $result['password'] == $_POST['password']){
	$_SESSION['username'] = $result['name'];
	$_SESSION['userid'] = $result['id'];
	echo true;
}
else{
	echo false;
}
?>

