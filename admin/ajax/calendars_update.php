<?php
include('../db_crud.php');

$calendarId = strip_tags(trim($_POST["calendarId"]));
$name = strip_tags(trim($_POST["calendarName"]));

$db = new db_connection();
$result = $db->updateData("calendars", array('name'), array($name), "id=?1",$calendarId);
echo $result;
?>