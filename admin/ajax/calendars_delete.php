<?php
include('../db_crud.php');

$calendarId = strip_tags(trim($_POST["calendarId"]));

$db = new db_connection();
$result = $db->deleteData("calendars", "id=?1",$calendarId);

echo $result;
?>