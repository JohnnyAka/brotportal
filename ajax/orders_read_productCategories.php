<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];

$userCategoryId = $db->getData("users", "customerCategory", "id = ?1", $userId)[0]['customerCategory'];

$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat = ?1",$userCategoryId);

$whereCondition = "";
foreach($visibleCategories as $category){
    $whereCondition .= $category['idProductCat'].' or ';
}
$whereCondition = rtrim($whereCondition, " or ");

$data = $db->getData("productCategories", array('id','name'), "id = ?1",$whereCondition);


$jsonData = json_encode($data);
echo $jsonData;
?>