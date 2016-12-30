<?php
include('../db_crud.php');
	
	$db = new db_connection();
	
	$customerDict = makeDict($db,'users', 'id', 'customerID');
	$preOrderCustomerDict = makeDict($db,'users', 'id', 'preOrderCustomerId');
	$productDict = makeDict($db,'products', 'id', 'productID');
	

	$time = date('H-i-s');
	$day = strip_tags(trim($_POST["day"]));
	$month = strip_tags(trim($_POST["month"]));
	$year = strip_tags(trim($_POST["year"]));
	$date = $year.'-'.$month.'-'.$day;

	$filename = 'bestellungen_'.$date.'_'.$time;
	$file = fopen($filename.'.csv', 'w');
	
	fputcsv($file, array('Artikelnummer', 'Kundennummer', 'Datum', 'Anzahl', 'Lieferung', 'LieferscheinNotiz'));
	
	$data = $db->getData("orders", 
	array('idProduct','idCustomer','orderDate','number','hook','important','noteBaking','noteDelivery'), 
	"orderDate='".$date."'");
	
	$orderList = [];
	for($x=0;$x<count($data);$x++){
		unset($data[$x]->noteBaking);
		unset($data[$x]->important);
		
	//$jsonData = json_encode($data[$x]['idProduct']);
	//echo $jsonData;
		
		$productIdReal = $productDict[$data[$x]['idProduct']];
		$data[$x]['idProduct'] = $productIdReal;
		$customerIdReal = $customerDict[$data[$x]['idCustomer']];
		$data[$x]['idCustomer'] = $customerIdReal;
		
		array_push($orderList, $data[$x]);
	}
	
	
	foreach ($orderList as $row)
	{
			fputcsv($file, $row);
	}
	 
	fclose($file);
	
	function makeDict($db, $table, $nameKey, $nameValue){
		$list = $db->getData($table, array($nameKey,$nameValue));
		
		foreach($list as $obj){
			$dict[$obj[$nameKey]] = $obj[$nameValue];
		}
		return $dict;
	}
?>