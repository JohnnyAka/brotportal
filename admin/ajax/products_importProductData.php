<?php

include('../db_crud.php');
	
$productObjects = json_decode($_POST["productObj"]);
$priceType = $_POST["priceT"];

switch($priceType){
	case 1:
		$priceX = 'price1';
		break;
	case 2:
		$priceX = 'price2';
		break;
	case 3:
		$priceX = 'price3';
		break;
	case 4:
		$priceX = 'price4';
		break;
	case 5:
		$priceX = 'price5';
		break;
	default:
		echo "Der Preis ist fehlerhaft, deshalb wird die Verarbeitung abgebrochen.";
		return false;
}



$db = new db_connection();

foreach($productObjects as $product){


	$result = $db->updateData("products", 
	array('ingredients','allergens','weight',$priceX), 
	array(str_replace(';',',',$product->zutaten),$product->allergene,$product->gewicht,str_replace(',','.',$product->preis)),
	"productID=?1",$product->artikelNummer);

	echo $result;
}

?>