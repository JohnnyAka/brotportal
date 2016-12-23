<?php
include('../db_crud.php');

$name = strip_tags(trim($_POST["calendarName"]));

$db = new db_connection();
$result = $db->createData("calendars",array('name'), array($name));

echo $result;
?>