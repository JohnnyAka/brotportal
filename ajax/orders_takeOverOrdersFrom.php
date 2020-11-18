<?php
session_start();

include('../db_crud.php');
include('../admin/permission_check_helpers.php');
include('../admin/classAjaxResponseMessage.php');
include('orders_helpers.php');

//block reload of shopping list
$_SESSION['dataBnotProducedForDisplay'] = true;

$important = '';
$noteDelivery = '';
$noteBaking = '';
$hook = 1;
$responseMessage = new AjaxResponseMessage;

$strDate = $_POST['orderDate'];
$idCustomer = $_POST['userID'];
$standardSlot = $_POST["standardSlot"];
$normalOrderMode = $_POST["normalOrderMode"];
$standardTakeoverSlot = $_POST["standardTakeoverSlot"];
unset($_POST['orderDate']);
unset($_POST['userID']);
unset($_POST['standardSlot']);
unset($_POST['normalOrderMode']);
unset($_POST['standardTakeoverSlot']);



if($normalOrderMode == "false"){
	if($standardSlot == 0){
		$responseString = "Es ist ein Fehler aufgetreten. Der Standardslot wurde nicht richtig angegeben. \n";
		$responseMessage->displayMessage = $responseString;
		$responseMessage->logMessage = $responseMessage;
		$responseMessage->success = false;
		echo json_encode($responseMessage);
		return;
	}
	$orderDate = getStandardOrderDate($standardSlot);
}else{
	$day = strtok($strDate, ".");
	$month = strtok(".");
	$year = strtok(".");
	$orderDate = $year."-".$month."-".$day;
}

$strTakeFromDate = $_POST['takeFromDate'];
unset($_POST['takeFromDate']);

if($standardTakeoverSlot != 0){
	$takeFromDate = getStandardOrderDate($standardTakeoverSlot);
}else{
	//format Date
	$day = strtok($strTakeFromDate, ".");
	$month = strtok(".");
	$year = strtok(".");
	$takeFromDate = $year."-".$month."-".$day;
}

$productNamesNotProduced =[];

$db = new db_connection();
$preProductCalendarDict = makeDict($db,'products', 'id', 'idCalendar');

if(!checkForPastAndAfterhour($db, $orderDate)){
    return;
}

$data = $db->getData("orders", 
	array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
	"idCustomer=?1 AND orderDate=?2", array($idCustomer,$takeFromDate));

foreach ($data as $order) {
	$order['orderDate'] = $orderDate;
	$productName = $db->getData("products", array('name'), "id=?1",$order['idProduct'])[0]['name'];
	//wenn es keine Standardbestellung ist und der Permission Test fehlschlägt
	if($normalOrderMode != 'false' && !checkForPermission($db, $order['idProduct'], $orderDate, $preProductCalendarDict)){
		array_push($productNamesNotProduced, $productName);
		//echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wird nicht in angemessener Zeit hergestellt. ";
		continue;
	}
	else{
		$result = $db->createData("orders",
			array('idProduct','idCustomer','orderDate','number','hook','important','noteDelivery','noteBaking'),
			$order);
		if(substr($result, 0, 1) != "N"){ //wenn die Datenbankaktion einen Fehler auslöst
			$responseMessage->logMessage .= $result;
			$responseMessage->displayMessage .= "Ein Fehler ist beim Erstellen des Artikels ".$productName." aufgetreten.\n";
			$responseMessage->false;
		}
	}
}

$notProducedCount = count($productNamesNotProduced);
if($notProducedCount){
	$multipleStr = "Der Artikel wird";
	if($notProducedCount > 1){
		$multipleStr = "Die Artikel werden";
	}
	$responseString = "Die Bestellung von ".compileNameString($productNamesNotProduced)." kann nicht übernommen werden. ".$multipleStr." nicht in angemessener Zeit hergestellt. \n";
	$responseMessage->displayMessage = $responseString;
	$responseMessage->success = false;
}

echo json_encode($responseMessage);

//unblock reload of shopping list
$_SESSION['dataBnotProducedForDisplay'] = false;

function compileNameString($names){
	$namesStr = "";
	$namesCount = count($names);
	for($x=0; $x<$namesCount; $x++){
		if($x < $namesCount-2){
			$namesStr.= $names[$x].", ";
		}
		elseif($x == $namesCount-2){
			$namesStr.= $names[$x]." und ";
		}
		else{
			$namesStr.= $names[$x];
		}
	}
	return $namesStr;
}




?>