<?php

$dir = $_POST['directory'];
if($dir == ''){
	return;
}
$images = scandir("../../images/advertisingImages/".$dir."/");
$allImgs = [];

	foreach($images as $img){
	    if($img == '.' || $img == '..') continue;
		array_push($allImgs, utf8_encode($img) );
	}

	$jsonData = json_encode($allImgs);
	echo $jsonData;

?>