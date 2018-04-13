<?php
session_start();
include('../admin/db_crud.php');

$id = $_SESSION['userid'];

$password = strip_tags(trim($_POST["passwordOld"]));
$passwordNew1 = strip_tags(trim($_POST["passwordNew1"]));
$passwordNew2 = strip_tags(trim($_POST["passwordNew2"]));

$db = new db_connection();
$currentPassword = $db->getData("users",array('password'),'id='.$id)[0]['password'];

if($currentPassword != $password){
    echo "Altes Passwort stimmt nicht.";
    return;
}
if($passwordNew1 != $passwordNew2){
    echo "Die neuen Passwort Felder stimmen nicht überein.";
    return;
}

$result = $db->updateData("users",array('password'),array($passwordNew1),"id=".$id);

echo $result;
?>