<?php
session_start();

include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');
//include('../admin/classAjaxResponseMessage.php');

//block reload of shopping list
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
$preProductCalendarDict = makeDict($db,'products', 'id', 'idCalendar');

//$responseMessage = new AjaxResponseMessage;

if(!checkForPastAndAfterhour($db, $orderDate)){
    $_SESSION['dataBlockedForDisplay'] = false;
    return;
}

foreach ($_POST as $id => $number) {
	if($number<0){
		echo "Values smaller than 0 are not processed";
		continue;
	}
    $productName = $db->getData("products", array('name'), "id='".$id."'")[0]['name'];
	$orderData = $db->getData("orders", array('locked'),
	"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);

	if($orderData[0]['locked']){
        echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wurde schon exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme. \n";
	    continue;
    }

    if(!checkForPermission($db, $id, $orderDate, $preProductCalendarDict)){
        echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wird nicht in angemessener Zeit hergestellt. \n";
        continue;
    }
    if($orderData){//same as orderExists
        if($number==0) {
            $result = $db->deleteData("orders",
                "idProduct=" . $id . " AND idCustomer=" . $idCustomer . " AND orderDate='" . $orderDate . "' AND hook=" . $hook);
        }
        else {
            $result = $db->updateData("orders",
                array('number', 'important', 'noteDelivery', 'noteBaking'),
                array($number, $important, $noteDelivery, $noteBaking),
                "idProduct=" . $id . " AND idCustomer=" . $idCustomer . " AND orderDate='" . $orderDate . "' AND hook=" . $hook);
        }
    }
    else{
        $result = $db->createData("orders",
        array('idProduct','idCustomer','orderDate','number','hook','important','noteDelivery','noteBaking'),
        array($id,$idCustomer,$orderDate,$number,$hook,$important,$noteDelivery,$noteBaking));
    }
echo $result;
}
//unblock reload of shopping list
$_SESSION['dataBlockedForDisplay'] = false;


?>