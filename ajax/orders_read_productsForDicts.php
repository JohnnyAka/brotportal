<?php
session_start();
include('../db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];
$userData = $db->getData("users", array("customerCategory","priceCategory"), "id = ?1",$userId)[0];

$userCategoryId = $userData['customerCategory'];

$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat = ?1",$userCategoryId);

if($userData['priceCategory'] == 0){
	$userPriceCategory = 'price2';
}else{
	$userPriceCategory = 'price'.$userData['priceCategory'];
}

$parameter = array('id','productID','name','orderPriority','productCategory','visibleForUser', $userPriceCategory.' as price');

$whereCondition = "";
$whereValues = array();
$x = 1;
foreach($visibleCategories as $category){
    $whereCondition .= '?'.$x.' or ';
		array_push($whereValues,$category['idProductCat']);
		$x++;
}
$whereCondition = rtrim($whereCondition, " or ");

$data = $db->getData("products", $parameter, "productCategory = ".$whereCondition,$whereValues);

$jsonData = json_encode($data);
echo $jsonData;
?>