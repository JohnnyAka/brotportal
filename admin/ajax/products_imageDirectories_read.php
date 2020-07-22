<?php

$directories = scandir("../../images/");
$newDir = [];

foreach($directories as $dir){
    if($dir == '.' || $dir == '..') continue;
	array_push($newDir, utf8_encode($dir) );
}

$jsonData = json_encode($newDir);
echo $jsonData;
?>