<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];

$userCategoryId = $db->getData("users", "customerCategory", "id=?1", $userId)[0]['customerCategory'];

$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat=?1",$userCategoryId);

$whereValues = array();
$whereQuery = "id=";
$x = 1;
foreach($visibleCategories as $category){
    array_push($whereValues, $category['idProductCat']);
    $whereQuery .= "?".$x." or ";
    $x++;
}
$whereQuery = rtrim($whereQuery, " or ");

$data = $db->getData("productCategories", array('id','name'), $whereQuery, $whereValues);


$jsonData = json_encode($data);
echo $jsonData;
?>