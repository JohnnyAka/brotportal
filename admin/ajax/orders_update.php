<?php
include('../db_crud.php');

$idProduct = strip_tags(trim($_POST["idProduct"]));
$number = strip_tags(trim($_POST["number"]));
$hook = (int)$_POST["hook"];
$important = (int)$_POST["important"];
$noteDelivery = strip_tags(trim($_POST["noteDelivery"]));
$noteBaking = strip_tags(trim($_POST["noteBaking"]));
$idCustomer = strip_tags(trim($_POST["idCustomer"]));
$orderDate = strip_tags(trim($_POST["orderDate"]));

$db = new db_connection();

$locked = $db->getData("orders",array('locked'),"idProduct=".$idProduct." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook)[0]['locked'];

if(!$locked){
	$result = $db->updateData("orders", 
		array('number','important','noteDelivery','noteBaking'), 
		array($number,$important,$noteDelivery,$noteBaking),
		"idProduct=".$idProduct." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook);
	//echo $number.' '.$important.' '.$noteDelivery.' '.$noteBaking.' '.$;
	//echo "idProduct=".$idProduct." AND idCustomer=".$idCustomer." AND orderDate='".$orderDate."' AND hook=".$hook;
}
else{
    $result = "Der Artikel kann nicht geändert werden, er wurde schon exportiert.";
}
echo $result;
?>