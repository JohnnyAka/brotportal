<?php



	

$images = scandir("../../images/advertisingImages/");
$allImgs = array();
	foreach($images as $img){
	    if($img == '.' || $img == '..') continue;
		array_push($allImgs, utf8_encode($img) );
	}

	$jsonData = json_encode($allImgs);
	echo $jsonData;

?>