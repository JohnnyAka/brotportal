<?php
include('../db_crud.php');
	
	$db = new db_connection();
	
	$customerDict = makeDict($db,'users', 'id', 'customerID');
	$preOrderCustomerDict = makeDict($db,'users', 'id', 'preOrderCustomerId');
	$productDict = makeDict($db,'products', 'id', 'productID');
	$preBakeDict = makeDict($db,'products', 'id', 'preBakeExp','preBakeExp!=0');	
	$preBakeMaxDict = makeDict($db,'products', 'id', 'preBakeMax','preBakeExp!=0');
	$preProductCalendarDict = makeDict($db,'products', 'id', 'idCalendar', 'preBakeExp!=0');

	$time = date('H-i-s');
	//get export date
	$day = strip_tags(trim($_POST["day"]));
	$month = strip_tags(trim($_POST["month"]));
	$year = strip_tags(trim($_POST["year"]));
	$date = $year.'-'.$month.'-'.$day;
	
	
	//return "Hier gehts";
	$orderList = getOrdersNormal($date);
	//return "Hier nicht mehr. evtl Datenbankconnection";
	
	$preOrderList = getPreOrders($date);
	//echo json_encode($preOrderList);
	
	$filename = '../exports/bestellungen_'.$date.'_'.$time;
	$file = fopen($filename.'.csv', 'w');
	fputcsv($file, array('Artikelnummer', 'Kundennummer', 'Datum', 'Anzahl', 'Lieferung', 'LieferscheinNotiz'));
	foreach ($orderList as $row)
		{fputcsv($file, $row);}
	foreach ($preOrderList as $row)
		{fputcsv($file, $row);}
	fclose($file);
	
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
	
	function prepareOrdersForExport($data, $normal=true){
		global $db, $customerDict, $productDict, $preBakeDict;
		
		$orderList = [];
		for($x=0;$x<count($data);$x++){
			$currentData = $data[$x];
			
			unset($currentData->noteBaking);
			unset($currentData->important);
			
			if($normal){
				$currentData['idCustomer'] = $customerDict[$currentData['idCustomer']];
				if(array_key_exists($currentData['idProduct'], $preBakeDict)){
					$currentData['hook'] = 5;
				}
				$currentData['idProduct'] = $productDict[$currentData['idProduct']];
				array_push($orderList, $currentData);
			}
			else{
				$data = $db->getData("users", array('preOrderCustomerId'), "id=".$currentData['idCustomer']);
				$currentData['idCustomer'] = $data[0]['preOrderCustomerId'];
				$currentData['idProduct'] = $productDict[$currentData['idProduct']];
				$currentData['orderDate'] = date_format(getExportDate(), 'Y-m-d');
				
				$orderList[] = $currentData;
			}
		}
		return $orderList;
	}
	
	function getOrdersNormal($date){
		global $db;
		
	
		$data = $db->getData("orders", 
		array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
		"orderDate='".$date."'");
		
		return prepareOrdersForExport($data);
	}

	
	//only Production "today"
	function getPreOrders($date){
		global $db, $customerDict, $productDict, $preBakeDict, $preBakeMaxDict, $preProductCalendarDict;
		
		$orderList = [];
		$exportDate = new DateTime($date);
		//iterate all products with prebake
		foreach($preBakeDict as $productId => $preBake){
			$minDate = $preBake;
			$maxDate = $preBakeMaxDict[$productId];
			
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
			for($x=getInteger($minDate); $x<=$maxDate; $x++){
				$exportToday = 'set';
				$deliveryDate = getExportDate();
				$deliveryDate->modify('+'.$x.' day');
				$deliveryDateFormated = date_format($deliveryDate,'Y-m-d');
				
				for($y=$x-1;$y>$minDate;$y--){
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
					$newOrders = prepareOrdersForExport($data, false);
					
					for($z=0;$z<count($newOrders);$z++){
						$currentData = $newOrders[$z];
						$currentData['noteDelivery'] = 'Lieferung am '.date_format($deliveryDate, 'd.m.Y');
						
						//check existing orders for multiple entries with same customer and product IDs
						//echo json_encode($orderList);
						$found = false;
						foreach($orderList as &$entry){
							//echo "Kunde aus Liste: ".$entry['idCustomer']." |Kunde akut: ".$currentData['idCustomer'];
							if($entry['idCustomer']==$currentData['idCustomer'] and $entry['idProduct']==$currentData['idProduct']){
								//echo " Number list: ".($entry['number']." Number current: ". $currentData['number'])." end\n";
								//echo " Number: ".($entry['number'] + $currentData['number'])." end\n";
								$entry['number'] = ($entry['number'] + $currentData['number']);
								//echo "in routine mit Anzahl: ".$entry['number'];
								$found = true;
							}
						}
						unset($entry);
						if($found == true){
							continue;
						}
						
						array_push($orderList, $currentData);
					}
				}
			}
		}
		return $orderList;
	}
?>

























