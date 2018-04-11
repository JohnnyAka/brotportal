<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$userPriceCategory = $db->getData("users", array('priceCategory'), "id=".$_SESSION['userid'])[0]['priceCategory'];
$userPriceCategory = 'price'.$userPriceCategory;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}

$data = $db->getData("products", $parameter, "id=".$_POST["id"]);

if($userPriceCategory !== "price0") {
    $data[0]['price'] = $data[0][$userPriceCategory];
}
unset($data[0][$userPriceCategory]);

$jsonData = json_encode($data);
echo $jsonData;
?>