<?php
include('../db_crud.php');

$db = new db_connection();
$data = $db->getData("categoryRelations", array('idUserCat','idProductCat'), 'idUserCat=?1',$_POST['userId']);

$jsonData = json_encode($data);
echo $jsonData;
?>

