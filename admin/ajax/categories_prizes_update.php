<?php
include('../db_crud.php');

$catId = strip_tags(trim($_POST["catId"]));
$infoText = strip_tags(trim($_POST["prizeCatInfo"]));

$db = new db_connection();
$result = $db->updateData("prizeCategories", array('infoText'), array($infoText), "id=".$catId);
echo $result;
?>