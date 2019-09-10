<?php
include('../db_crud.php');

$dir = "../exports/";
$files = array();

if (is_dir($dir)){
    if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            $files[] = $file;
        }
        closedir($dh);
    }
}
sort($files);
$jsonData = json_encode($files);
echo $jsonData;
?>

