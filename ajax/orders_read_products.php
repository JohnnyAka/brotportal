<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];

$userCategoryId = $db->getData("users", "customerCategory", "id = ?1",$userId)[0]['customerCategory'];

$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat = ?1",$userCategoryId);

$whereCondition = "";
$whereValues = array();
$x = 1;
foreach($visibleCategories as $category){
    $whereCondition .= '?'.$x.' or ';
		array_push($whereValues,$category['idProductCat']);
		$x++;
}
$whereCondition = rtrim($whereCondition, " or ");

$data = $db->getData("products", array('id','productID','name','productCategory'), "productCategory = ".$whereCondition,$whereValues);

$jsonData = json_encode($data);
echo $jsonData;
?>