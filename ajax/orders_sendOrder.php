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

if(!checkForPastAndAfterhour($db, $orderDate)){
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

    if(!checkForPermission($db, $id, $orderDate, $preProductCalendarDict)){   //check, ob Artikel noch in angemessener Zeit hergestellt wird
			if(!($number==$orderData[0]['number'] and $hook == $orderData[0]['hook'])){
				array_push($notProducedTime, $productName);
			}
			continue;
    }
    if($orderData){//same as orderExists
        if($number==0) {
            $result = $db->deleteData("orders",
                "idProduct=" . $id . " AND idCustomer=" . $idCustomer . " AND orderDate='" . $orderDate . "' AND hook=" . $hook);
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
								"idProduct=" . $id . " AND idCustomer=" . $idCustomer . " AND orderDate='" . $orderDate . "' AND hook=" . $hook);
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

if(count($notProducedExport)){
	$responseMessage->appendDisplayMessage("Die Bestellung von ".compileNameString($notProducedExport)." kann nicht aufgegeben werden. Der Artikel wurde schon exportiert. Bitte melden Sie sich diesbezüglich bei der Bestellannahme.\n");
}
if(count($notProducedTime)){
	$responseMessage->appendDisplayMessage("Die Bestellung von ".compileNameString($notProducedTime)." kann nicht aufgegeben werden. Der Artikel wird nicht in angemessener Zeit hergestellt.\n");
}

echo json_encode($responseMessage);

//unblock reload of shopping list
$_SESSION['dataBlockedForDisplay'] = false;

function compileNameString($names){
	$namesStr = '';
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