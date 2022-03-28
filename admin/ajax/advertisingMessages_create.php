<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["name"]));
$messageImage = strip_tags(trim($_POST["messageImage"]));
$messageHeader = strip_tags(trim($_POST["messageHeader"]));
$messageText = strip_tags(trim($_POST["messageText"]));
$popupStartDate = strip_tags(trim($_POST["popupStartDate"]));
$popupEndDate = strip_tags(trim($_POST["popupEndDate"]));
$messageboxStartDate = strip_tags(trim($_POST["messageboxStartDate"]));
$messageboxEndDate = strip_tags(trim($_POST["messageboxEndDate"]));
$linkedProductId = strip_tags(trim($_POST["linkedProductId"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));


$db = new db_connection();
$result = $db->createData("advertisingMessages",array('name', 'messageImage','messageHeader','messageText','popupStartDate','popupEndDate','messageboxStartDate','messageboxEndDate','linkedProductId','orderPriority'), 
	array($name, $messageImage, $messageHeader, $messageText, $popupStartDate, $popupEndDate, $messageboxStartDate, $messageboxEndDate, $linkedProductId, $orderPriority));

echo $result;
?>

