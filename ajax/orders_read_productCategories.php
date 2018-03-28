<?php
session_start();
include('../admin/db_crud.php');

$db = new db_connection();

$userId = $_SESSION['userid'];

$visibleCategories = $db->getData("categoryrelations", "idProductCat", "idUserCat = '".$userId."'");

$whereCondition = "";
foreach($visibleCategories as $category){
    $whereCondition .= $category['idProductCat'].' or ';
}
$whereCondition = rtrim($whereCondition, " or ");

$data = $db->getData("productCategories", array('id','name'), "id = ".$whereCondition);


$jsonData = json_encode($data);
echo $jsonData;
?>