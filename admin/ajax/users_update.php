<?php
include('../db_crud.php');

$id = strip_tags(trim($_POST["id"]));

$customerID = strip_tags(trim($_POST["customerid"]));
$name = strip_tags(trim($_POST["name"]));
$password = strip_tags(trim($_POST["password"]));
$customerCategory = strip_tags(trim($_POST["customerCategoryer"]));
$mailAdressTo = strip_tags(trim($_POST["mailAdressTo"]));
$mailAdressReceive = strip_tags(trim($_POST["mailAdressReceive"]));
$telephone1 = strip_tags(trim($_POST["telephone1"]));
$telephone2 = strip_tags(trim($_POST["telephone2"]));
$fax = strip_tags(trim($_POST["fax"]));
$storeAdress = strip_tags(trim($_POST["storeAdress"]));
$whereToPutOrder = strip_tags(trim($_POST["whereToPutOrder"]));


$db = new db_connection();
$result = $db->updateData("users", 
array('customerID','name','password','customerCategory','mailAdressTo','mailAdressReceive','telephone1','telephone2','fax','storeAdress','whereToPutOrder'), 
array($customerID,$name,$password,$customerCategory,$mailAdressTo,$mailAdressReceive,$telephone1,$telephone2,$fax,$storeAdress,$whereToPutOrder),
"id=".$id);

echo $result;
?>