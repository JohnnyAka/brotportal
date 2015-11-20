<?php
			include('db_crud.php');
			$db = new db_connection();
			
			/* //createData Prüfung
			echo "Here we go: " . $db->createData("products", array('productID','name','description'), array('90','Bombenstimmung','Schwabell Wabbel'));
			*/
			/*//getData Prüfung
			foreach($db->getData("products",array("productID","name"), "name='Gaumenball'") as $value){
				foreach($value as $subvalue){
					echo "Get get get: " . $subvalue . '<br />';
				}	
			}*/
			/*//updateData Prüfung
			echo $db->updateData("products", "productID", "345","id = 1");
			*/
			//deleteData Prüfung
			//echo $db->deleteData("products", "id=1");
		?>