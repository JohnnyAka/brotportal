<?php
session_start();
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("users", array('customerID','name'), "id=?1",$_SESSION["userid"]);

//$logDate = $_POST['logDateTime'];

error_log($_POST['logType']." | ".$data[0]['customerID']." | ".$data[0]['name']." | ".$_POST['logMessage']);

/*$logFile = fopen("../logs/".$idCustomer." ".$name.".txt", "a");
fwrite($logFile, $logType." ".$logDate." ".$logMessage."\r\n");
fclose($logFile);*/

?>