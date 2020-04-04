<?php
session_start();

include('../db_crud.php');

$pSearchText = strip_tags(trim($_POST['productSearchText']));

$db = new db_connection();

$userId = $_SESSION['userid'];

//prepare prices
$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$userId)[0]['priceCategory'];
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
$queryString = rtrim($queryString, 'and');

//prepare product visibility
$userCategoryId = $db->getData("users", "customerCategory", "id=?1", $userId)[0]['customerCategory'];
$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat=?1",$userCategoryId);
$whereValuesVisibility = array();
$whereQueryVisibility = "(";
$x = count($pSearchTextArray) + 1;
foreach($visibleCategories as $category){
    array_push($whereValuesVisibility, $category['idProductCat']);
    $whereQueryVisibility .= "productCategory=?".$x." or ";
    $x++;
}
$whereQueryVisibility = rtrim($whereQueryVisibility, " or ");
$whereQueryVisibility .= ')';

$testQuery = $queryString." and visibleForUser != '0' and ".$whereQueryVisibility;

$data = $db->getData("products", $parameter, $testQuery, array_merge($pSearchTextArray, $whereValuesVisibility));

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