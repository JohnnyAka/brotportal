<?php
include('../admin/db_crud.php');


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
            echo "Die Bestellung von ".$productName." kann nicht abgeschickt werden. Der Artikel wird nicht in angemessener Zeit hergestellt. ";
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
echo $result;
}

function makeDict($db, $table, $nameKey, $nameValue, $whereStatement=NULL){
    $list = $db->getData($table, array($nameKey,$nameValue),$whereStatement);
    $dict = NULL;

    foreach($list as $obj){
        $dict[$obj[$nameKey]] = $obj[$nameValue];
    }
    return $dict;
}

function checkForPermission($db, $productId, $specifiedDate, $preProductCalendarDict){
    //get calendar for product
    $calendarDatesRaw = $db->getData("calendarsDaysRelations",
        array('date'), "idCalendar='".$preProductCalendarDict[$productId]."'");

    $dateNow = new DateTime("now");

    //hole min und max; rechne die Daten aus und packe sie in array
    $orderDate = new DateTime($specifiedDate);
    $productDetails = $db->getData("products", array('preBakeExp','preBakeMax'), "id='".$productId."'")[0];
    $minDate = clone $orderDate;
    $minDate->modify('-'.$productDetails['preBakeExp'].' day');
    $maxDate = clone $orderDate;
    $maxDate->modify('-'.$productDetails['preBakeMax'].' day');
    //for schleife->ist eines der min-max daten in calendarDates?
    $check = false;
    foreach ($calendarDatesRaw as $cDate){
        $currentDate = new DateTime($cDate['date']);
        if($currentDate <= $minDate && $currentDate >= $maxDate && $currentDate >= $dateNow){
            $check = true;
            continue;
        }
    }
    return $check;
}

?>