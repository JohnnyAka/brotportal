<?php
include('../db_crud.php');

$messageId = strip_tags(trim($_POST["idUp"]));
$name = strip_tags(trim($_POST["nameUp"]));
$messageImage = strip_tags(trim($_POST["messageImageUp"]));
$messageHeader = strip_tags(trim($_POST["messageHeaderUp"]));
$messageText = strip_tags(trim($_POST["messageTextUp"]));
$popupStartDate = date('Y-m-d', strtotime($_POST["popupStartDateUp"]));
$popupEndDate = date('Y-m-d', strtotime($_POST["popupEndDateUp"]));
$messageboxStartDate = date('Y-m-d', strtotime($_POST["messageboxStartDateUp"]));
$messageboxEndDate = date('Y-m-d', strtotime($_POST["messageboxEndDateUp"]));
$linkedProductId = strip_tags(trim($_POST["linkedProductIdUp"]));
$orderPriority = strip_tags(trim($_POST["orderPriorityUp"]));


$db = new db_connection();
$result = $db->updateData("advertisingMessages",array('name', 'messageImage','messageHeader','messageText','popupStartDate','popupEndDate','messageboxStartDate','messageboxEndDate','linkedProductId','orderPriority'), 
	array($name, $messageImage, $messageHeader, $messageText, $popupStartDate, $popupEndDate, $messageboxStartDate, $messageboxEndDate, $linkedProductId, $orderPriority), "id=?1",$messageId);

echo $result;
?>