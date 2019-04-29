<?php
session_start(); 
while($_SESSION['dataBlockedForDisplay']){
usleep(50000);
}
?>

