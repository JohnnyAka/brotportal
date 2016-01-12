<?php
include('../db_crud.php');

$id = strip_tags(trim($_POST["id"]));

$productid = strip_tags(trim($_POST["productid"]));
$name = strip_tags(trim($_POST["name"]));
$productCategory = strip_tags(trim($_POST["productCategory"]));
$visibleForUser = (int)$_POST["visibleForUser"];
$description = strip_tags(trim($_POST["description"]));
$imagePath = strip_tags(trim($_POST["imagePath"]));
$ingredients = strip_tags(trim($_POST["ingredients"]));
$allergens = strip_tags(trim($_POST["allergens"]));
$weight = strip_tags(trim($_POST["weight"]));
$preBakeExp = strip_tags(trim($_POST["preBakeExp"]));
$featureExp = strip_tags(trim($_POST["featureExp"]));

$db = new db_connection();
$result = $db->updateData("products", 
array('productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp'), 
array($productid,$name,$productCategory,$visibleForUser,$description,$imagePath,$ingredients,$allergens,$weight,$preBakeExp,$featureExp),
"id=".$id);

echo $result;
?>