<?php
include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');


$important = '';
$noteDelivery = '';
$noteBaking = '';
$hook = 1;

$strDate = $_POST['orderDate'];
$idCustomer = $_POST['userID'];
unset($_POST['orderDate']);
unset($_POST['userID']);
//format Date
$day = strtok($strDate, ".");
$month = strtok(".");
$year = strtok(".");
$orderDate = $year."-".$month."-".$day;


$db = new db_connection();

if(!checkForPastAndAfterhour($db, $orderDate)){
    return;
}

$result = $db->deleteData("orders",
"idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook='1'");

echo $result;

?>