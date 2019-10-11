<?php
session_start();
include('../db_crud.php');
include('../admin/classAjaxResponseMessage.php');

$id = $_SESSION['userid'];

$password = strip_tags(trim($_POST["passwordOld"]));

$responseMessage = new AjaxResponseMessage;

$db = new db_connection();
$currentPassword = $db->getData("users",array('password'),'id=?1',$id)[0]['password'];

if($currentPassword != $password){
		$responseMessage->displayMessage = "Altes Passwort stimmt nicht.";
		$responseMessage->success = false;
		echo json_encode($responseMessage);
    return;
}

$mailAdressTo = strip_tags(trim($_POST["mailAdressTo"]));
$mailAdressReceive = strip_tags(trim($_POST["mailAdressReceive"]));

$result = $db->updateData("users", 
array('mailAdressTo','mailAdressReceive'),
array($mailAdressTo,$mailAdressReceive),
"id=?1",$id);

if(substr($result, 0, 1) != "R"){  
	$responseMessage->appendLogMessage("Die Mailadresse konnte nicht geändert werden. Fehlermeldung: ".$result);
	$responseMessage->appendDisplayMessage("Programmfehler: Die Mailadresse konnte nicht geändert werden. Bitte melden Sie sich in der Bäckerei\n");
	$responseMessage->success = false;
}

echo json_encode($responseMessage);
?>