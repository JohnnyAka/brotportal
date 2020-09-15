<?php
session_start();

include('../db_crud.php');

$pSearchText = strip_tags(trim($_POST['productSearchText']));

$db = new db_connection();

$userId = $_SESSION['userid'];

//prepare prices
$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$userId)[0]['priceCategory'];
$userPriceCategory = 'price'.$priceCatNumber;

$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePathSmall','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}

$pSearchTextArray = array(); $queryStringName = ''; $queryStringID = '';
$pSearchTextArray = explode(' ', $pSearchText, 3);
$pSearchIdArray = $pSearchTextArray;

//build searchqueries (search for name and search for productID)
$countSearchArray = count($pSearchTextArray);
for($x = 1; $x <= $countSearchArray; $x++){
	$pSearchTextArray[$x-1] = '%'.$pSearchTextArray[$x-1].'%';
	$queryStringName .= ' name LIKE ?'.$x.' and';
	$queryStringID .= " productID=?".$x." or";
}
$queryStringName = rtrim($queryStringName, 'and');
$queryStringID = rtrim($queryStringID, 'or');

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

$nameQuery = $queryStringName." and visibleForUser != '0' and ".$whereQueryVisibility;
$idQuery = $queryStringID." and visibleForUser != '0' and ".$whereQueryVisibility;

$nameData = $db->getData("products", $parameter, $nameQuery, array_merge($pSearchTextArray, $whereValuesVisibility));

$productIdData = $db->getData("products", $parameter, $idQuery, array_merge($pSearchIdArray, $whereValuesVisibility));

function compareProducts($a, $b){
    return strcasecmp($a['name'],$b['name']);
}
usort($nameData, "compareProducts");


$finalData = $nameData;
foreach($productIdData as $productIdObj){
    $found = false;
    foreach($nameData as $productNameObj){
        if($productNameObj['id'] == $productIdObj['id']){
            $found = true;
        }
    }
    if(!$found){
        array_unshift($finalData, $productIdObj);
    }
}

$result = $db->getData("prizeCategories", array('infoText'), "id=?1",$priceCatNumber);
if ($result != null){
    $priceCatInfoText = $result[0]['infoText'];
}
else{
    $priceCatInfoText = '';
}

if($userPriceCategory !== "price0" && is_array($finalData) ) {
	foreach ($finalData as $index => $product){
		$finalData[$index]['price'] = $product[$userPriceCategory];
		unset($finalData[$index][$userPriceCategory]);
		$finalData[$index]['priceInfoText'] = $priceCatInfoText;
	}
}
	
$jsonData = json_encode($finalData);
echo $jsonData;


?>