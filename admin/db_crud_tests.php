<?php
session_start();
if(!isset($_SESSION['trustedUser'])) {
   die("Bitte erst einloggen");  
}
			include('db_crud.php');
			$db = new db_connection();
			
			/* //createData Prüfung
			echo "Here we go: " . $db->createData("products", array('productID','name','description'), array('90','Bombenstimmung','Schwabell Wabbel'));
			*/
			/*//getData Prüfung
			//$result = $db->getData("products",array("productID","name"), "name=?1","Gaumenball");
			$result = $db->getData("products",array("productID","name"), "name=?1 and productID=?2",array("Gaumenball","321654987"));
			foreach($result as $value){
				foreach($value as $subvalue){
					echo "Get get get: " . $subvalue . '<br />';
				}	
			}*/
			//updateData Prüfung
			//echo $db->updateData("products", "name", "ChemiebrotNr3","name=?1 and productID=?2",array("ChemiebrotNr2",321654987));
			
			//deleteData Prüfung
			//echo $db->deleteData("products", "productID=?1 and name=?2", array("1232323124","Biobrot 2000"));
		?>