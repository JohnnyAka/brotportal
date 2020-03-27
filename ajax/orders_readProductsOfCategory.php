<?php
session_start();
include('../db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];
$userCategoryId = $db->getData("users", "customerCategory", "id=?1", $userId)[0]['customerCategory'];


$priceCatNumber = $db->getData("users", array('priceCategory'), "id=?1",$_SESSION['userid'])[0]['priceCategory'];
$userPriceCategory = 'price'.$priceCatNumber;
$parameter = array('id','productID','name','productCategory','visibleForUser','description','imagePath','ingredients','allergens','weight','preBakeExp','featureExp');
//add the price if pricecategory not "0"
if($userPriceCategory !== "price0"){
    array_push($parameter, $userPriceCategory);
}



$treeDepth = $_POST["categoryTreeDepth"];
$categoryId = $_POST["categoryID"];
$categories = array();
$categories[] = $categoryId;
$data = array();

$tdCount=1;
$subCategories = getSubCategories($db, $categoryId, $userCategoryId, $treeDepth, $tdCount);
$categories = array_merge($categories, $subCategories);


function getSubCategories($db, $productCatID, $userCategoryId, $treeDepth, $tdCount){
     
    if($tdCount >= $treeDepth){
        return;
    }
    $tdCount++;

    $foundCategories = array();

	$categoriesOfLayer = $db->getData("productCategories", array('id'), "upperCategoryID=?1",$productCatID);
	foreach($categoriesOfLayer as $cat){
        $categoryVisible = $db->getData("categoryRelations", array("idProductCat"), "idUserCat=?1 and idProductCat=?2",array($userCategoryId, $cat['id']));
        if($categoryVisible) {
            $foundCategories[] = $cat['id'];
            $categoriesTemp = getSubCategories($db, $cat['id'], $userCategoryId, $treeDepth, $tdCount);
            if($categoriesTemp != null) {
                //$foundCategories = $foundCategories + $categoriesTemp;
                $foundCategories = array_merge($foundCategories, $categoriesTemp);
            }
        }
	}
	return $foundCategories;
}

foreach($categories as $catId){
	$productsOfCat = $db->getData("products", $parameter, "productCategory=?1 and visibleForUser != '0'",$catId);
	$data = array_merge($data, $productsOfCat);
}

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