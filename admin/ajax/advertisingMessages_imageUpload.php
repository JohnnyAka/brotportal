<?php

$productImage = json_decode($_POST["image"]);


list($type, $data) = explode(';', $productImage);
list(, $data)      = explode(',', $data);

$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

$directory = $_POST["directory"];
$fileName = $_POST["name"];

$imageDir = "../../images/advertisingImages/".$directory."/";

//$file = file_get_contents($productImage);
file_put_contents($imageDir.$fileName, $data);

echo "Bild/er erfolgreich hochgeladen"

?>