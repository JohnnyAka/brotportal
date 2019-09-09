<?php
//Note: Produkte mit mindest Vorbestell-Zeit werden mindestens X(prebake) vor der Auslieferung produziert, nicht X(prebake) Produktionstage

include($_SERVER['DOCUMENT_ROOT']."/brotportal/admin/db_crud.php");
	
	$db = new db_connection();
	
	$customerDict = makeDict($db,'users', 'id', 'customerID');
	$preOrderCustomerDict = makeDict($db,'users', 'id', 'preOrderCustomerId');
	$productDict = makeDict($db,'products', 'id', 'productID');
	$preBakeDict = makeDict($db,'products', 'id', 'preBakeExp','preBakeMax!=0');	
	$preBakeMaxDict = makeDict($db,'products', 'id', 'preBakeMax','preBakeMax!=0');
	$preProductCalendarDict = makeDict($db,'products', 'id', 'idCalendar');

	$time = date('H-i-s');
	//get export date
	$day = strip_tags(trim($_POST["day"]));
	$month = strip_tags(trim($_POST["month"]));
	$year = strip_tags(trim($_POST["year"]));
	$date = $year.'-'.$month.'-'.$day;
	
	
	//return "Hier gehts";
	$orderList = getOrdersNormal($db, $customerDict, $productDict, $preBakeDict, $preProductCalendarDict, $date);
	//return json_encode($orderList);
	
	$preOrderList = getPreOrders($db, $customerDict, $productDict, $preBakeDict, $preBakeMaxDict, $preProductCalendarDict, $date);
	//return json_encode($preOrderList);
	
	$filename = $_SERVER['DOCUMENT_ROOT'].'/brotportal/admin/exports/bestellungen_'.$date.'_'.$time;
	$file = fopen($filename.'.csv', 'w');
	fputcsv_eol($file, array('Datum', 'Kundennummer', 'Artikelnummer', 'Anzahl', 'Lieferung1', 'Lieferung2', 'Lieferung3','Extra','Nachlieferung','Retour', 'LieferscheinNotiz'),"\r\n");
	foreach ($orderList as $row)
		{
			if($row['hook'] == 5){
				$writeOrder = array($row['orderDate'],$row['idCustomer'],$row['idProduct'],0,0,0,0,0,$row['number'],0,$row['noteDelivery']);
			}
			else{
				$writeOrder = array($row['orderDate'],$row['idCustomer'],$row['idProduct'],$row['number'],0,0,0,0,0,$row['noteDelivery']);
			}
			fputcsv_eol($file, $writeOrder,"\r\n");
		}
	foreach ($preOrderList as $row)
		{fputcsv_eol($file, $row,"\r\n");}
	fclose($file);
	
	function fputcsv_eol($fp, $array, $eol) {
		fputcsv($fp, $array);
		if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
			fwrite($fp, $eol);
		}
	}
	
	function makeDict($db, $table, $nameKey, $nameValue, $whereStatement=NULL){
		$list = $db->getData($table, array($nameKey,$nameValue),$whereStatement);
		$dict = NULL;
		
		foreach($list as $obj){
			$dict[$obj[$nameKey]] = $obj[$nameValue];
		}
		return $dict;
	}
	
	function getExportDate(){
	global $date;
		return new DateTime($date);
	}
	
	function getInteger($minDate){
		return $minDate;
	}
	
	//checks, whether a product is being baked on specified date; returns true or false
	function isBakedOnDate($db, $productId, $specifiedDate, $preProductCalendarDict){
		//get calendar for product
		$calendarDatesRaw = $db->getData("calendarsDaysRelations", 
		array('date'), "idCalendar='".$preProductCalendarDict[$productId]."'");

		$calendarDates = [];
		foreach($calendarDatesRaw as $cDate){
			array_push($calendarDates, $cDate['date']);
		}
		//is date in calendar?
		$date = date_format($specifiedDate, 'Y-m-d');
		if(!in_array($date, $calendarDates)){ 
			return false;
		}
		else{
			return true;
		}
	}
	
	function prepareOrdersForExport($db, $customerDict, $productDict, $preBakeDict, $preProductCalendarDict, $data, $normal=true){
		if($data) {
			$orderList = [];
			for ($x = 0; $x < count($data); $x++) {
				$currentData = $data[$x];

				unset($currentData->noteBaking);
				unset($currentData->important);
				
//set locked property to indicate that it has been exported
				$db->updateData("orders", array('locked'), array('1'), "orderDate='".$currentData['orderDate']."' AND idCustomer=".$currentData['idCustomer']." AND idProduct=".$currentData['idProduct']." AND hook=".$currentData['hook']);

				if ($normal) {
						$currentData['idCustomer'] = $customerDict[$currentData['idCustomer']];
						//stattdessen: wenn es heut nicht produziert wird oder wenn es einen prebake von >0 hat, bekommt es hook 5
						if ($preBakeDict[$currentData['idProduct']] != 0 OR !isBakedOnDate($db, $currentData['idProduct'], getExportDate(), $preProductCalendarDict)) {
								$currentData['hook'] = 5;
						}
						$currentData['idProduct'] = $productDict[$currentData['idProduct']];
						array_push($orderList, $currentData);
				} else {
						$data = $db->getData("users", array('preOrderCustomerId'), "id=" . $currentData['idCustomer']);
						$currentData['idCustomer'] = $data[0]['preOrderCustomerId'];
						$currentData['idProduct'] = $productDict[$currentData['idProduct']];
						$currentData['orderDate'] = date_format(getExportDate(), 'Y-m-d');

						$orderList[] = $currentData;
				}
			}
			return $orderList;
		}
	else false;
	}
	
	function getOrdersNormal($db, $customerDict, $productDict, $preBakeDict, $preProductCalendarDict, $date){
	
		$data = $db->getData("orders", 
		array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
		"orderDate='".$date."'");
		
		return prepareOrdersForExport($db, $customerDict, $productDict, $preBakeDict, $preProductCalendarDict, $data);
	}

	
	//only Production "today"
	function getPreOrders($db, $customerDict, $productDict, $preBakeDict, $preBakeMaxDict, $preProductCalendarDict, $date){
		
		$orderList = [];
		$exportDate = new DateTime($date);
		//iterate all products with prebake
		foreach($preBakeMaxDict as $productId => $preBakeMax){
			$minDate = $preBakeDict[$productId];
			$maxDate = $preBakeMax;


			$productNow = $productDict[$productId];
			$productNow;


			$calendarDatesRaw = $db->getData("calendarsDaysRelations",
			array('date'), "idCalendar='".$preProductCalendarDict[$productId]."'");

			$calendarDates = [];
			foreach($calendarDatesRaw as $cDate){
				array_push($calendarDates, $cDate['date']);
			}
			
			$exportDate = date_format(getExportDate(), 'Y-m-d');
			//if today no production day of product, continue
			if(!in_array($exportDate, $calendarDates)){ 
				continue;
			}
			
			//check all possible dates for this product
			$minDateOriginal = $minDate;
			if($minDate == 0) $minDate += 1; //do not check delivery date (already covered in "normal" case)
			// x Schleife stellt den möglichen Tag der Lieferung ein
			for($x=getInteger($minDate); $x<=$maxDate; $x++){
				$exportToday = 'set';
				$deliveryDate = getExportDate();
				$deliveryDate->modify('+'.$x.' day');
				$deliveryDateFormated = date_format($deliveryDate,'Y-m-d');
				// y Schleife wandert die möglichen näheren Produktionsdaten ab; wird eins gefunden -> unset
				for($y=$x-1;$y>=$minDateOriginal;$y--){
					$productionDate = new DateTime($deliveryDateFormated);
					$productionDate->modify('-'.$y.' day');
					$productionDate = date_format($productionDate,'Y-m-d');
					if(in_array($productionDate, $calendarDates))
					{
						$exportToday = 'unset';
						break;
					}
				}
				if($exportToday=='set'){
					//echo $productId.' ?'.$exportToday.'? '.$deliveryDateFormated.'| ';
					$data = $db->getData("orders", 
					array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
					"idProduct=".$productId." AND orderDate='".$deliveryDateFormated."'");
					//echo json_encode($data);
					$newOrders = prepareOrdersForExport($db, $customerDict, $productDict, $preBakeDict, $preProductCalendarDict, $data, false);
					if($newOrders != false) {
						for ($z = 0; $z < count($newOrders); $z++) {
							$currentData = $newOrders[$z];
							$currentData['noteDelivery'] = 'Lieferung: '. $currentData['number'] .' am ' . date_format($deliveryDate, 'd.m.Y');

							//check existing orders for multiple entries with same customer and product IDs
							//echo json_encode($orderList);
							$found = false;
							foreach ($orderList as &$entry) {
								//echo "Kunde aus Liste: ".$entry['idCustomer']." |Kunde akut: ".$currentData['idCustomer'];
								if ($entry['idCustomer'] == $currentData['idCustomer'] and $entry['idProduct'] == $currentData['idProduct']) {
									//echo " Number list: ".($entry['number']." Number current: ". $currentData['number'])." end\n";
									//echo " Number: ".($entry['number'] + $currentData['number'])." end\n";
									$entry['number'] = ($entry['number'] + $currentData['number']);
									$entry['noteDelivery'] .= ', Lieferung: '. $currentData['number'] .' am ' . date_format($deliveryDate, 'd.m.Y');
									//echo "in routine mit Anzahl: ".$entry['number'];
									$found = true;
								}
							}
							unset($entry);
							if ($found == true) {
								continue;
							}
							array_push($orderList, $currentData);
						}
					}
				}
			}
		}
		return $orderList;
	}
?>

























