<?php
session_start();
include('../db_crud.php');

$db = new db_connection();
$result = $db->getData("users", array('warningThreshold','discountRelative','autoSendOrders'), "id=?1",$_SESSION['userid']);

$jsonData = json_encode($result);
echo $jsonData;
?>