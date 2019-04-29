<?php
session_start();

include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');

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

if(!checkForPastAndAfterhour($db, $orderDate)){
    return;
}

foreach ($_POST as $id => $number) {
	if($number<0){
		echo "Values smaller than 0 are not processed";
		continue;
	}

	$orderExists = $db->getData("orders", array('hook'),
	"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);

	if($number!=0){
		if(!checkForPermission($db, $id, $orderDate, $preProductCalendarDict)){
					$productName = $db->getData("products", array('name'), "id='".$id."'")[0]['name'];
					echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wird nicht in angemessener Zeit hergestellt. \n";
					continue;
		}
		if($orderExists){
			$result = $db->updateData("orders",
			array('number','important','noteDelivery','noteBaking'),
			array($number,$important,$noteDelivery,$noteBaking),
			"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);
		}
		else{
			$result = $db->createData("orders",
			array('idProduct','idCustomer','orderDate','number','hook','important','noteDelivery','noteBaking'),
			array($id,$idCustomer,$orderDate,$number,$hook,$important,$noteDelivery,$noteBaking));
		}
	}
	else{
		$result = $db->deleteData("orders",
		"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);
	}
	//unblock reload of shopping list
	$_SESSION['dataBlockedForDisplay'] = false;
echo $result;
}



?>