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
	$day = strip_tags(trim($_POST["day"]));
	$month = strip_tags(trim($_POST["month"]));
	$year = strip_tags(trim($_POST["year"]));
	$date = $year.'-'.$month.'-'.$day;
	
	$orderList = getOrdersNormal($date);
	
	$preOrderList = getPreOrders($date);
	//echo json_encode($preOrderList);
	
	$filename = 'bestellungen_'.$date.'_'.$time;
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
			}
			else{
				$data = $db->getData("users", array('preOrderCustomerId'), "id=".$currentData['idCustomer']);
				$currentData['idCustomer'] = $data[0]['preOrderCustomerId'];
			}
			
			$currentData['idProduct'] = $productDict[$currentData['idProduct']];
			
			array_push($orderList, $currentData);
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
		
							$myfile = fopen("testfile.txt", "w") ;
		
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
			if(!in_array($exportDate, $calendarDates)){ 
				continue;
			}
			
			//check all possible dates for this product
			for($x=getInteger($minDate); $x<=$maxDate; $x++){
				$exportToday = 'set';
				$deliveryDate = getExportDate();
				$deliveryDate->modify('+'.$x.' day');
				$deliveryDateFormated = date_format($deliveryDate,'Y-m-d');
				//echo ' X: '.$x.' % ';
				
				for($y=$x-1;$y>$minDate;$y--){
					//echo ' Y: '.$y.' % ';
					//echo ' minDate: '.$minDate.' % ';
					$productionDate = new DateTime($deliveryDateFormated);
					$productionDate->modify('-'.$y.' day');
					$productionDate = date_format($productionDate,'Y-m-d');
					//echo '>>'.$productionDate.'<<';
					if(in_array($productionDate, $calendarDates))
					{
						$exportToday = 'unset';
						//echo '!';
						break;
					}
				}
				if($exportToday=='set'){
					//echo $productId.' ?'.$exportToday.'? '.$deliveryDateFormated.'| ';
					$data = $db->getData("orders", 
					array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
					"idProduct=".$productId." AND orderDate='".$deliveryDateFormated."'");
					$newOrders = prepareOrdersForExport($data, false);
					
					for($z=0;$z<count($newOrders);$z++){
						$currentData = $newOrders[$z];
						$currentData['orderDate'] = $exportDate;
						$currentData['noteDelivery'] = 'Lieferung am '.date_format($deliveryDate, 'd.m.Y');
						
						array_push($orderList, $newOrders[$z]);
					}
				}
			}
		}
		return $orderList;
	}
?>

























