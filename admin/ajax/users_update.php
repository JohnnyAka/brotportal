<?php
include('../db_crud.php');

$id = strip_tags(trim($_POST["id"]));

$customerID = strip_tags(trim($_POST["customerid"]));
$name = strip_tags(trim($_POST["name"]));
$password = strip_tags(trim($_POST["password"]));
if($password == ''){ $resetPassword = false;}
else{$resetPassword = true;}
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$customerCategory = strip_tags(trim($_POST["customerCategory"]));
$discountRelative = strip_tags(trim($_POST["discountRelative"]));
$warningThreshold = strip_tags(trim($_POST["warningThreshold"]));
$autoSendOrders = (int)$_POST["autoSendOrders"];
$mailAdressTo = strip_tags(trim($_POST["mailAdressTo"]));
$mailAdressReceive = strip_tags(trim($_POST["mailAdressReceive"]));
$telephone1 = strip_tags(trim($_POST["telephone1"]));
$telephone2 = strip_tags(trim($_POST["telephone2"]));
$fax = strip_tags(trim($_POST["fax"]));
$storeAdress = strip_tags(trim($_POST["storeAdress"]));
$whereToPutOrder = strip_tags(trim($_POST["whereToPutOrder"]));
$priceCategory = strip_tags(trim($_POST["priceCategory"]));
$preOrderCustomerId = strip_tags(trim($_POST["preOrderCustomerId"]));

$db = new db_connection();

if($resetPassword){
$result = $db->updateData("users", 
	array('customerID','name','password','customerCategory','discountRelative','warningThreshold','autoSendOrders','mailAdressTo','mailAdressReceive','telephone1','telephone2','fax','storeAdress','whereToPutOrder','priceCategory','preOrderCustomerId'), 
	array($customerID,$name,$passwordHash,$customerCategory,$discountRelative,$warningThreshold,$autoSendOrders,$mailAdressTo,$mailAdressReceive,$telephone1,$telephone2,$fax,$storeAdress,$whereToPutOrder,$priceCategory,$preOrderCustomerId),
	"id=?1",$id);
}else{
	$result = $db->updateData("users", 
	array('customerID','name','customerCategory','discountRelative','warningThreshold','autoSendOrders','mailAdressTo','mailAdressReceive','telephone1','telephone2','fax','storeAdress','whereToPutOrder','priceCategory','preOrderCustomerId'), 
	array($customerID,$name,$customerCategory,$discountRelative,$warningThreshold,$autoSendOrders,$mailAdressTo,$mailAdressReceive,$telephone1,$telephone2,$fax,$storeAdress,$whereToPutOrder,$priceCategory,$preOrderCustomerId),
	"id=?1",$id);
}

echo $result;
?>