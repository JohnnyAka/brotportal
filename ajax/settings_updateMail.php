<?php
session_start();
include('../admin/db_crud.php');

$id = $_SESSION['userid'];

$password = strip_tags(trim($_POST["passwordOld"]));

$db = new db_connection();
$currentPassword = $db->getData("users",array('password'),'id='.$id)[0]['password'];

if($currentPassword != $password){
    echo "Passwort stimmt nicht.";
    return;
}

$mailAdressTo = strip_tags(trim($_POST["mailAdressTo"]));
$mailAdressReceive = strip_tags(trim($_POST["mailAdressReceive"]));

$result = $db->updateData("users", 
array('mailAdressTo','mailAdressReceive'),
array($mailAdressTo,$mailAdressReceive),
"id=".$id);

echo $result;
?>