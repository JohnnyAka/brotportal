<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();
$data = $db->getData("users", array('customerID','name'), "id=".$_SESSION["userid"]);


$logDate = $_POST['logDateTime'];
$idCustomer = $data[0]['customerID'];
$name = $data[0]['name'];
unset($_POST['logDateTime']);
unset($_POST['userID']);

$logType = $_POST['logType'];
$logMessage = $_POST['logMessage'];

$logFile = fopen("../logs/".$idCustomer." ".$name.".txt", "a");
fwrite($logFile, $logType." ".$logDate." ".$logMessage."\r\n");
fclose($logFile);

?>