<?php

$productImage = json_decode($_POST["image"]);

$imageSize = $_POST["imgSize"];

switch($imageSize){
	case 1:
		$imgSizeDir = 'small';
		break;
	case 2:
		$imgSizeDir = 'medium';
		break;
	case 3:
		$imgSizeDir = 'big';
		break;
	default:
		$imgSizeDir = 'medium';
}

list($type, $data) = explode(';', $productImage);
list(, $data)      = explode(',', $data);

$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

$directory = $_POST["directory"];
$fileName = $_POST["name"];

$imageDir = "../../images/".$imgSizeDir."/".$directory."/";

//$file = file_get_contents($productImage);
file_put_contents($imageDir.$fileName, $data);

echo "Bilder erfolgreich hochgeladen"

?>