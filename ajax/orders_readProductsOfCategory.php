<?php
session_start();
include('../db_crud.php');

$db = new db_connection();

$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$_SESSION['userid'])[0]['priceCategory'];
 
$userPriceCategory = 'price'.$priceCatNumber;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}

$data = $db->getData("products", $parameter, "productCategory=?1 and visibleForUser != '0'",$_POST["categoryID"]);

$result = $db->getData("prizeCategories", array('infoText'), "id=?1",$priceCatNumber);
if ($result != null){
    $priceCatInfoText = $result[0]['infoText'];
}
else{
    $priceCatInfoText = '';
}

if($userPriceCategory !== "price0" && is_array($data) ) {
	foreach ($data as $index => $product){
		$data[$index]['price'] = $product[$userPriceCategory];
		unset($data[$index][$userPriceCategory]);
		$data[$index]['priceInfoText'] = $priceCatInfoText;
	}
}
	
$jsonData = json_encode($data);
echo $jsonData;
?>