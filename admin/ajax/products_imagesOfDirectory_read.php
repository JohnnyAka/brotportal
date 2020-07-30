<?php

$dir = $_POST['directory'];
/*if($dir == NULL){
	$dir = '';
}*/


$size = $_POST['size'];


switch ($size){
	case 'medium': 
		$sizeReal = 'medium';
		break;
	case 'small': 
		$sizeReal = 'small';
		break;
	case 'big': 
		$sizeReal = 'big';
		break;
	default:
		$sizeReal = 'medium';
}
	

$images = scandir("../../images/".$sizeReal."/".$dir."/");
$newDir = [];
if($dir == ''){
	$jsonData = json_encode($newDir);
	echo $jsonData;
}else{
	foreach($images as $dir){
	    if($dir == '.' || $dir == '..') continue;
		array_push($newDir, utf8_encode($dir) );
	}

	$jsonData = json_encode($newDir);
	echo $jsonData;
}
?>