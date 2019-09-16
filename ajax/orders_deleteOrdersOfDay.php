<?php
session_start();
include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');
include('../admin/classAjaxResponseMessage.php');

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

$responseMessage = new AjaxResponseMessage;
$productNamesLocked =[];

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
				array_push($productNamesLocked, $productName);
        //echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wurde schon exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme. \n";
        continue;
    }
    else{
        $result = $db->deleteData("orders","idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND idProduct=".$entry['idProduct']." AND hook=".$entry['hook']);
        if(substr($result, 0, 1) != "R"){ //wenn die Datenbankaktion einen Fehler auslöst
					$responseMessage->logMessage .= $result;
					$productName = $db->getData("products", array('name'), "id='".$entry['idProduct']."'")[0]['name'];
					$responseMessage->displayMessage .= "Ein Fehler ist beim Löschen des Artikels".$productName." aufgetreten.";
					$responseMessage->false;
				}
    }
}
$lockedCount = count($productNamesLocked);
if($lockedCount){
	$multipleStr = "Der Artikel wurde";
	if($lockedCount > 1){
		$multipleStr = "Die Artikel wurden";
	}
	$responseString = "Die Bestellung von ".compileNameString($productNamesLocked)." kann nicht geändert werden. ".$multipleStr." bereits exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme. \n";
	$responseMessage->displayMessage = $responseString;
	$responseMessage->success = false;
}
echo json_encode($responseMessage);
//old solution
//$result = $db->deleteData("orders","idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook='1'");

$_SESSION['dataBlockedForDisplay'] = false;

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