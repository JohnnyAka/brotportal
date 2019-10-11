<?php
session_start();
include('../db_crud.php');
include('../admin/classAjaxResponseMessage.php');

$id = $_SESSION['userid'];

$password = strip_tags(trim($_POST["passwordOld"]));
$passwordNew1 = strip_tags(trim($_POST["passwordNew1"]));
$passwordNew2 = strip_tags(trim($_POST["passwordNew2"]));

$responseMessage = new AjaxResponseMessage;

$db = new db_connection();
$currentPassword = $db->getData("users",array('password'),'id=?1',$id)[0]['password'];

if($currentPassword != $password){
		$responseMessage->displayMessage = "Altes Passwort stimmt nicht.";
		$responseMessage->success = false;
		echo json_encode($responseMessage);
    return;
}
if($passwordNew1 != $passwordNew2){
    $responseMessage->displayMessage = "Die neuen Passwort Felder stimmen nicht überein.";
		$responseMessage->success = false;
		echo json_encode($responseMessage);
    return;
}

$result = $db->updateData("users",array('password'),array($passwordNew1),"id=?1",$id);
		
if(substr($result, 0, 1) != "R"){  
	$responseMessage->appendLogMessage("Das Passwort konnte nicht geändert werden. Fehlermeldung: ".$result);
	$responseMessage->appendDisplayMessage("Programmfehler: Das Passwort konnte nicht geändert werden. Bitte melden Sie sich in der Bäckerei\n");
	$responseMessage->success = false;
}
	
echo json_encode($responseMessage);
?>