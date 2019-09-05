<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$priceCatNumber = $db->getData("users", array('priceCategory'), "id=".$_SESSION['userid'])[0]['priceCategory'];
 
$userPriceCategory = 'price'.$priceCatNumber;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}

$data = $db->getData("products", $parameter, "id=".$_POST["id"]);

if($userPriceCategory !== "price0") {
    $data[0]['price'] = $data[0][$userPriceCategory];
}

$priceCatInfoText = $db->getData("prizeCategories", array('infoText'), "id=".$priceCatNumber)[0]['infoText'];
$data[0]['priceInfoText'] = $priceCatInfoText;
unset($data[0][$userPriceCategory]);

$jsonData = json_encode($data);
echo $jsonData;
?>