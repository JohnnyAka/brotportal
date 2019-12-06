<?php
session_start();
include('../db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];

$userCategoryId = $db->getData("users", "customerCategory", "id=?1", $userId)[0]['customerCategory'];

$visibleCategories = $db->getData("categoryRelations", "idProductCat", "idUserCat=?1",$userCategoryId);

$whereValues = array();
//$whereQuery = "id=";
$whereQuery = "";
$x = 1;
foreach($visibleCategories as $category){
    array_push($whereValues, $category['idProductCat']);
    $whereQuery .= "id=?".$x." or ";
    $x++;
}
$whereQuery = rtrim($whereQuery, " or ");

$data = $db->getData("productCategories", array('id','name','orderPriority','upperCategoryID'), $whereQuery, $whereValues);


$jsonData = json_encode($data);
echo $jsonData;
?>