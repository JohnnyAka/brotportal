<?php
session_start();
include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');

$_SESSION['dataBlockedForDisplay'] = true;

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
    $_SESSION['dataBlockedForDisplay'] = false;
    return;
}

$data = $db->getData("orders", array('idProduct','locked','hook'),
    "idCustomer=".$idCustomer." AND orderDate='".$orderDate."'");

foreach ($data as $entry) {
    if($entry['locked']){
        $productName = $db->getData("products", array('name'), "id='".$entry['idProduct']."'")[0]['name'];
        echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wurde schon exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme. \n";
        continue;
    }
    else{
        $result = $db->deleteData("orders","idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND idProduct=".$entry['idProduct']." AND hook=".$entry['hook']);
        echo $result;
    }
}

//old solution
//$result = $db->deleteData("orders","idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook='1'");

$_SESSION['dataBlockedForDisplay'] = false;

?>