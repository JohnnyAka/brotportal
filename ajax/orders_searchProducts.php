<?php
session_start();

include('../db_crud.php');

$pSearchText = $_POST['productSearchText'];


$db = new db_connection();

$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$_SESSION['userid'])[0]['priceCategory'];
$userPriceCategory = 'price'.$priceCatNumber;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}


$pSearchText = '%'.$pSearchText.'%';


//Achtug!!!!!!!!!!!!!!!!!!!!!!!!!!!! die 1 am Ende der nächsten Zeile muss geändert werden, wie auch productCategory
$data = $db->getData("products", $parameter, "name LIKE ?1 and visibleForUser != '0'",$pSearchText);

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