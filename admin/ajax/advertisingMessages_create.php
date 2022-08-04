<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["name"]));
$messageImage = strip_tags(trim($_POST["messageImage"]));
$imageDirectory = strip_tags(trim($_POST["imageDirectory"]));
$messageHeader = strip_tags(trim($_POST["messageHeader"]));
$messageText = strip_tags(trim($_POST["messageText"]));
$popupStartDate = date('Y-m-d', strtotime($_POST["popupStartDate"]));
$popupEndDate = date('Y-m-d', strtotime($_POST["popupEndDate"]));
$messageboxStartDate = date('Y-m-d', strtotime($_POST["messageboxStartDate"]));
$messageboxEndDate = date('Y-m-d', strtotime($_POST["messageboxEndDate"]));
$linkedProductId = strip_tags(trim($_POST["linkedProductId"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));

if($messageImage != ''){
	$messageImage = $imageDirectory.'/'.$messageImage;
}

$db = new db_connection();
$result = $db->createData("advertisingMessages",array('name', 'messageImage','messageHeader','messageText','popupStartDate','popupEndDate','messageboxStartDate','messageboxEndDate','linkedProductId','orderPriority'), 
	array($name, $messageImage, $messageHeader, $messageText, $popupStartDate, $popupEndDate, $messageboxStartDate, $messageboxEndDate, $linkedProductId, $orderPriority));

echo $result;
?>

