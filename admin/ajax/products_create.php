<?php
include('../db_crud.php');

$productid = strip_tags(trim($_POST["productid"]));
$name = strip_tags(trim($_POST["name"]));
$productCategory = strip_tags(trim($_POST["productCategory"]));
$orderPriority = strip_tags(trim($_POST["orderPriority"]));
$visibleForUser = (int)$_POST["visibleForUser"];
$description = strip_tags(trim($_POST["description"]));
$imageDirectory = strip_tags(trim($_POST["imageDirectory"]));
$imagePath = strip_tags(trim($_POST["imagePath"]));
$imagePathSmall = strip_tags(trim($_POST["imagePathSmall"]));
$imagePathBig = strip_tags(trim($_POST["imagePathBig"]));
$ingredients = strip_tags(trim($_POST["ingredients"]));
$allergens = strip_tags(trim($_POST["allergens"]));
$weight = strip_tags(trim($_POST["weight"]));
$preBakeExp = strip_tags(trim($_POST["preBakeExp"]));
$preBakeMax = strip_tags(trim($_POST["preBakeMax"]));
$featureExp = strip_tags(trim($_POST["featureExp"]));
$price1 = strip_tags(trim($_POST["price1"]));
$price2 = strip_tags(trim($_POST["price2"]));
$price3 = strip_tags(trim($_POST["price3"]));
$price4 = strip_tags(trim($_POST["price4"]));
$price5 = strip_tags(trim($_POST["price5"]));
$idCalendar = (int)$_POST["idCalendar"];

//set paths of image directories
if($imagePath){
	$imagePath = $imageDirectory.'/'.$imagePath;	
}
if($imagePathSmall){
	$imagePathSmall = $imageDirectory.'/'.$imagePathSmall;
}
if($imagePathBig){
	$imagePathBig = $imageDirectory.'/'.$imagePathBig;
}


$db = new db_connection();
	$result = $db->createData(
	"products", 
	array('productID','name','productCategory','orderPriority','visibleForUser','description','imagePath','imagePathSmall','imagePathBig','ingredients','allergens','weight','preBakeExp','preBakeMax','featureExp','price1','price2','price3','price4','price5','idCalendar'), 
	array($productid,$name,$productCategory, $orderPriority, $visibleForUser,$description,$imagePath,$imagePathSmall,$imagePathBig,$ingredients,$allergens,$weight,$preBakeExp,$preBakeMax,$featureExp,$price1,$price2,$price3,$price4,$price5,$idCalendar)
);



echo $result;
?>