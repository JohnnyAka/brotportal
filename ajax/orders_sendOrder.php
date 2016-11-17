<?php
include('../db_crud.php');


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
foreach ($_POST as $id => $number) {
	if($number<0){
		echo "Values smaller than 0 are not processed";
		continue;
	}
	
	$orderExists = $db->getData("orders", array('hook'), 
	"idProduct=".$id." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);
	
	if($number!=0){
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

?>