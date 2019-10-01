<?php
session_start();

include('../admin/db_crud.php');
include('../admin/permission_check_helpers.php');
include('../admin/classAjaxResponseMessage.php');

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

$responseMessage = new AjaxResponseMessage;
$notProducedExport = [];
$notProducedTime = [];

$responseTmp = checkForPastAndAfterhour($db, $orderDate); //Abbruch, wenn es nach dem Bestellschluss oder in der Vergangenheit ist
if(!$responseTmp[0]){
		$responseMessage->appendDisplayMessage($responseTmp[1]);
		$responseMessage->success = false;
		echo json_encode($responseMessage);
    $_SESSION['dataBlockedForDisplay'] = false;
    return;
}

foreach ($_POST as $id => $number) {
	if($number<0){
		$responseMessage->appendDisplayMessage("Werte kleiner als 0 werden nicht verarbeitet.");
		continue;
	}
    $productName = $db->getData("products", array('name'), "id='".$id."'")[0]['name'];
	$orderData = $db->getData("orders", array('locked','number','hook'),
	"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);

	if(isset($orderData[0]) and $orderData[0]['locked']){   //check, ob Artikel schon exportiert wurde
		if(!($number==$orderData[0]['number'] and $hook == $orderData[0]['hook'])){
			array_push($notProducedExport, $productName);
		}
		continue;
	}

	if(!checkForPermission($db, $id, $orderDate, $preProductCalendarDict)) {   //check, ob Artikel noch in angemessener Zeit hergestellt wird
		if(isset($orderData[0]) and $number == $orderData[0]['number'] and $hook == $orderData[0]['hook']) {
				//do nothing
		}
		else{
			array_push($notProducedTime, $productName);
		}
		continue;
	}
	if($orderData){//same as orderExists
			if($number==0) {
					$result = $db->deleteData("orders",
							"idProduct=?1 AND idCustomer=?2 AND orderDate=?3 AND hook=?4", array($id,$idCustomer,$orderDate,$hook));
					//Fehler abfangen und ausgeben
					if(substr($result, 0, 1) != "R"){   //Der Anfang des Antwortstrings von createData in db_crud.php in admin/. Zugegeben, das ist Pfusch, aber derzeit scheint es sinnvoller, als ALLE query Antworten neu zu schreiben.
						$responseMessage->appendLogMessage("Die Bestellung von ".$productName." konnte nicht gelöscht werden. Fehlermeldung: ".$result);
						$responseMessage->appendDisplayMessage("Programmfehler: Die Bestellung von ".$productName." konnte nicht gelöscht werden. Bitte melden Sie sich in der Bäckerei\n");
					}
			}
			else {
					$result = $db->updateData("orders",
							array('number', 'important', 'noteDelivery', 'noteBaking'),
							array($number, $important, $noteDelivery, $noteBaking),
							"idProduct=?1 AND idCustomer=?2 AND orderDate=?3 AND hook=?4", array($id,$idCustomer,$orderDate,$hook));
					if(substr($result, 0, 1) != "R"){   //Der Anfang des Antwortstrings von createData in db_crud.php in admin/. Zugegeben, das ist Pfusch, aber derzeit scheint es sinnvoller, als ALLE query Antworten neu zu schreiben.
						$responseMessage->appendLogMessage("Die Bestellung von ".$productName." konnte nicht geändert werden. Fehlermeldung: ".$result);
						$responseMessage->appendDisplayMessage("Programmfehler: Die Bestellung von ".$productName." konnte nicht geändert werden. Bitte melden Sie sich in der Bäckerei\n");
					}
			}
	}
	else{
			$result = $db->createData("orders",
			array('idProduct','idCustomer','orderDate','number','hook','important','noteDelivery','noteBaking'),
			array($id,$idCustomer,$orderDate,$number,$hook,$important,$noteDelivery,$noteBaking));
			if(substr($result, 0, 1) != "N"){//Der Anfang des Antwortstrings von createData in db_crud.php in admin/. Zugegeben, das ist Pfusch, aber derzeit scheint es sinnvoller, als ALLE query Antworten neu zu schreiben.
					$responseMessage->appendLogMessage("Die Bestellung von ".$productName." konnte nicht gespeichert werden. Fehlermeldung: ".$result);
					$responseMessage->appendDisplayMessage("Programmfehler: Die Bestellung von ".$productName." konnte nicht gespeichert werden. Bitte melden Sie sich in der Bäckerei\n");
			}
	}
}
$exportCount = count($notProducedExport);
if($exportCount){
	$multipleStr = " wurde";
	if($exportCount > 1){
		$multipleStr = "en wurden";
	}
	$responseMessage->appendDisplayMessage("Die Bestellung von ".compileNameString($notProducedExport)." kann nicht geändert werden. Die Bestellung".$multipleStr." schon exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme.\n");
	$responseMessage->success = false;
}
$timeCount = count($notProducedTime);
if($timeCount){
	$multipleStr = "Das Produkt wird";
	if($timeCount > 1){
		$multipleStr = "Die Produkte werden";
	}
	$responseMessage->appendDisplayMessage("Die Bestellung von ".compileNameString($notProducedTime)." kann nicht aufgegeben werden. ".$multipleStr." nicht in angemessener Zeit hergestellt.\n");
	$responseMessage->success = false;
}


echo json_encode($responseMessage);
//echo json_encode([$responseMessage->success,$responseMessage->logMessage,$responseMessage->displayMessage]);
//echo json_encode(["success"=>$responseMessage->success,"logMessage"=>$responseMessage->logMessage,"displayMessage"=>$responseMessage->displayMessage]);//json_encode($responseMessage);

//unblock reload of shopping list
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