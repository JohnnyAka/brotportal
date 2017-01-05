<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("products", array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','preBakeMax','featureExp','price1','price2','price3','price4','price5','idCalendar'), "id=".$_POST["id"]);


$jsonData = json_encode($data);
echo $jsonData;
?>