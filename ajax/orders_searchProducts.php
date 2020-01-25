<?php
session_start();

include('../db_crud.php');

$pSearchText = strip_tags(trim($_POST['productSearchText']));

$db = new db_connection();

$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$_SESSION['userid'])[0]['priceCategory'];
$userPriceCategory = 'price'.$priceCatNumber;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}

$pSearchTextArray = array(); $queryString = '';
$pSearchTextArray = explode(' ', $pSearchText, 3);

//build searchquery
$countSearchArray = count($pSearchTextArray);
for($x = 1; $x <= $countSearchArray; $x++){
	$pSearchTextArray[$x-1] = '%'.$pSearchTextArray[$x-1].'%';
	$queryString .= ' name LIKE ?'.$x.' and';
}
$queryString = chop($queryString, 'and');

$data = $db->getData("products", $parameter, $queryString." and visibleForUser != '0'",$pSearchTextArray);

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