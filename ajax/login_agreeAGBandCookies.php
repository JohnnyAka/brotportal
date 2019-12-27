<?php
session_start();
include('../db_crud.php');

$userId = $_SESSION['userid'];

$db = new db_connection();
$result = $db->updateData("users", array('agreedAGBs'),array(true), "id=?1",$userId);

echo $result;

?>

