<?php
session_start();
if(!isset($_SESSION['trustedUser'])) {
   die("Bitte erst einloggen");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Joldelunder Brotportal</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
				<!--jquery files -->
		<link href="../../jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="../../jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="../../jquery-ui-1.11.4.custom/jquery-ui.js"></script>

      <?php
        require '../orders_functions.php';
      ?>
    <!-- Custom styles for this template -->
	<link href="css/admin.css" rel="stylesheet">
	<link href="css/products.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
  </head>

  <body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Joldelunder</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="products.php">Produkte</a></li>
            <li><a href="users.php">Benutzer</a></li>
            <li><a href="orders.php">Bestellungen</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kategorien <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="categories_users.php">Kundenkategorien</a></li>
                <li><a href="categories_products.php">Produktkategorien</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="categories_relations.php">Kunden - Produkte</a></li>
              </ul>
            </li>
						<li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kalender <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="calendars.php">Kalender</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="calendars_days.php">Kalender - Tage</a></li>
              </ul>
            </li>
						<li><a href="export.php">Export</a></li>						
						<li><a href="settings.php">Einstellungen</a></li>
						<li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
	  <div class="row mainrow">
	    <div class="col-md-3">
            <h3>Produktliste</h3>
            <ul class="sidebarList listsHeight">
                <?php
                include('db_crud.php');
                $db = new db_connection();
                //get visible cats
                $visibleCats = $db->getData("productCategories",array("id"));
                //get products and make products dictionary and category dictionary
                //produces a an associative array with key = product ID and objects with keys: "id","productID","name","productCategory","visibleForUser"
                //for category dict: associative array with key = category ID and value = name
                //example: $productDict['8']['name'] ; $categoryDict['5']
                $productDict = array(); $categoryNameDict = array(); $categoryOrderDict = array();
                foreach($visibleCats as $category){
                    $queryResult = $db->getData("products",array("id","productID","name","productCategory", "orderPriority"), "productCategory=".$category['id']);
                    $arrayLength = count($queryResult);
                    for($x=0; $x < $arrayLength; $x++){
                        $productDict[$queryResult[$x]['id']] = $queryResult[$x];
                    }
                    $categoryEntry = $db->getData("productCategories",array("id","name","orderPriority"), "id=".$category['id'])[0];
                    $categoryNameDict[$category['id']] = $categoryEntry['name'];
                    $categoryOrderDict[$category['id']] = $categoryEntry['orderPriority'];
                }
                uasort($categoryOrderDict, function($a, $b){
                    if(intval($a)<intval($b)){return -1;}
                    return 1;
                });
                foreach($categoryOrderDict as $catId => $orderPriority){
                    $catName = $categoryNameDict[$catId];
                    echo "<li class='sidebarElement showMultipleArticles' data-id=".$catId.">".$catName."<span class='icon-list-collapse glyphicon glyphicon-collapse-down' aria-hidden=\"true\"></span></li>";
                    echo '<ul class="subSidebarList" style="display:none;">';
                    $productsOfCategory = search($productDict, 'productCategory', $catId);
                    usort($productsOfCategory, function($a, $b) {
                        if($a['orderPriority'] == $b['orderPriority']){
                            return strcasecmp($a['name'], $b['name']);
                        }
                        elseif($a['orderPriority'] < $b['orderPriority']){
                            return -1;
                        }
                        else{
                            return 1;
                        }
                    });
                    foreach($productsOfCategory as $product){
                        echo "<li class='subSidebarElement showSingleArticle' data-id=".$product['id'].">".$product['name']."</li>";
                    }
                    echo '</ul>';
                }
                ?>
			</ul>
	    </div>
	    <div class="col-md-9 main-content">
		<div id="messages"></div>
		<div class="button_group handleData">
		  <button type="button" class="btn btn-primary createProductButton">
			Artikel anlegen
		  </button>
		  <button type="button" class="btn btn-primary updateProductButton" >
			Artikel &auml;ndern
		  </button>
		  <button type="button" class="btn btn-primary deleteProductButton" >
			Artikel l&ouml;schen
		  </button>
		</div> 
		<h1>Ausgew&auml;hlter Artikel</h1>
		  <p>
			Artikel Nr.: <span class="displayProductID"></span><br />
		  Name: <span class="displayName"></span><br />
			Veröffentlicht: <span class="displayVisibleForUser"></span><br />
			Kategorie: <span class="displayProductCategory"></span><br />
			Listen Priorit&auml;t: <span class="displayOrderPriority"></span><br />
			Beschreibung: <span class="displayDescription"></span><br />
			Bildpfad: <span class="displayImagePath"></span><br />
			Inhaltsstoffe: <span class="displayIngredients"></span><br />
			Allergene: <span class="displayAllergens"></span><br />
			Gewicht: <span class="displayWeight"></span><br />
			Tage vorher backen: <span class="displayPreBakeExp"></span><br />
			Maximal Tage vorher backen: <span class="displayPreBakeMax"></span><br />
			Notiz (Pre-Notification): <span class="displayFeatureExp"></span><br />
			Preis1: <span class="displayPrice1"></span><br />
			Preis2: <span class="displayPrice2"></span><br />
			Preis3: <span class="displayPrice3"></span><br />
			Preis4: <span class="displayPrice4"></span><br />
			Preis5: <span class="displayPrice5"></span><br />
			Kalender: <span class="displayIdCalendar"></span><br />
		  </p>
		</div>
	  </div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update product -->
	<div class="modal" id="createProduct" tabindex="-1" role="dialog" aria-labelledby="createProductLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createProductLabel">Artikel anlegen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createProductForm" method="post" action="ajax/products_create.php">
				<div class="field">
					<label for="productid">Artikel Nr.:</label>
					<input type="text" id="productid" name="productid" required>
				</div>
				<div class="field">
					<label for="name">Name:</label>
					<input type="text" id="name" name="name" required>
				</div>
				<div class="field">
					<input type="hidden" name="visibleForUser" value="0">
				</div>
				<div class="field">
					<label for="visibleForUser">Für Kunden sichtbar:</label>
					<input type="checkbox" id="visibleForUser" name="visibleForUser" value="1">
				</div>
				<div class="field">
					<label for="description">Beschreibung:</label>
					<textarea id="description" name="description"></textarea>
				</div>
				<div class="field">
					<label for="productCategory">Produkt-Kategorie:</label>
					<select id="productCategory" name="productCategory" required>
					</select>
				</div>
				<div class="field">
					<label for="orderPriority">Listen Priorität:</label>
					<input type="number" min="1" max="99" value="50" id="orderPriority" name="orderPriority">
				</div>
				<div class="field">
					<label for="imagePath">Bild-Pfad:</label>
					<input type="text" id="imagePath" name="imagePath">
				</div>
				<div class="field">
					<label for="ingredients">Inhaltsstoffe:</label>
					<textarea id="ingredients" name="ingredients"></textarea>
				</div>
				<div class="field">
					<label for="allergens">Allergene:</label>
					<textarea id="allergens" name="allergens"></textarea>
				</div>
				<div class="field">
					<label for="weight">Gewicht:</label>
					<input type="text" id="weight" name="weight">
				</div>
				<div class="field">
					<label for="preBakeExp">Tage vorher Backen:</label>
					<input type="number" id="preBakeExp" name="preBakeExp">
				</div>
				<div class="field">
					<label for="preBakeMax">Maximal Tage vorher Backen:</label>
					<input type="number" id="preBakeMax" name="preBakeMax">
				</div>
				<div class="field">
					<label for="featureExp">Besonderheiten (Pre-Notifikation):</label>
					<textarea id="featureExp" name="featureExp"></textarea>
				</div>
				<div class="field">
					<label for="price1">Preis 1:</label>
					<input type="number" step="0.01" id="price1" name="price1">
				</div>
				<div class="field">
					<label for="price2">Preis 2:</label>
					<input type="number" step="0.01" id="price2" name="price2">
				</div>
				<div class="field">
					<label for="price3">Preis 3:</label>
					<input type="number" step="0.01" id="price3" name="price3">
				</div>
				<div class="field">
					<label for="price4">Preis 4:</label>
					<input type="number" step="0.01" id="price4" name="price4">
				</div>
				<div class="field">
					<label for="price5">Preis 5:</label>
					<input type="number" step="0.01" id="price5" name="price5">
				</div>
				<div class="field">
					<label for="idCalendar">Kalender:</label>
					<select id="idCalendar" name="idCalendar" required>
					</select>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createProductForm" class="btn btn-primary createProduct">Artikel speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateProduct" tabindex="-1" role="dialog" aria-labelledby="updateProductLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateProductLabel">Artikel &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateProductForm" method="post" action="ajax/products_update.php">
				<div class="field">
					<label for="productidUp">Artikel Nr.:</label>
					<input type="text" id="productidUp" name="productid" required>
				</div>
				<div class="field">
					<label for="nameUp">Name:</label>
					<input type="text" id="nameUp" name="name" required>
				</div>
				<div class="field">
					<input type="hidden" name="visibleForUser" value="0">
				</div>
				<div class="field">
					<label for="visibleForUserUp">Für Kunden sichtbar:</label>
					<input type="checkbox" id="visibleForUserUp" name="visibleForUser" value="1">
				</div>
				<div class="field">
					<label for="productCategoryUp">Produkt-Kategorie:</label>
					<select id="productCategoryUp" name="productCategory" required>
					</select>
				</div>
				<div class="field">
					<label for="orderPriorityUp">Listen Priorität:</label>
					<input type="number" min="1" max="99" id="orderPriorityUp" name="orderPriority">
				</div>
				<div class="field">
					<label for="descriptionUp">Beschreibung:</label>
					<textarea id="descriptionUp" name="description"></textarea>
				</div>
				<div class="field">
					<input type="hidden" id="idUp" name="id">
				</div>
				<div class="field">
					<label for="imagePathUp">Bild-Pfad:</label>
					<input type="text" id="imagePathUp" name="imagePath">
				</div>
				<div class="field">
					<label for="ingredientsUp">Inhaltsstoffe:</label>
					<textarea id="ingredientsUp" name="ingredients"></textarea>
				</div>
				<div class="field">
					<label for="allergensUp">Allergene:</label>
					<textarea id="allergensUp" name="allergens"></textarea>
				</div>
				<div class="field">
					<label for="weightUp">Gewicht:</label>
					<input type="text" id="weightUp" name="weight">
				</div>
				<div class="field">
					<label for="preBakeExpUp">Tage vorher Backen:</label>
					<input type="number" id="preBakeExpUp" name="preBakeExp">
				</div>
				<div class="field">
					<label for="preBakeMaxUp">Maximal Tage vorher Backen:</label>
					<input type="number" id="preBakeMaxUp" name="preBakeMax">
				</div>
				<div class="field">
					<label for="featureExpUp">Besonderheiten (Pre-Notifikation):</label>
					<textarea id="featureExpUp" name="featureExp"></textarea>
				</div>
				<div class="field">
					<label for="price1Up">Preis 1:</label>
					<input type="number" step="0.01" id="price1Up" name="price1">
				</div>
				<div class="field">
					<label for="price2Up">Preis 2:</label>
					<input type="number" step="0.01" id="price2Up" name="price2">
				</div>
				<div class="field">
					<label for="price3Up">Preis 3:</label>
					<input type="number" step="0.01" id="price3Up" name="price3">
				</div>
				<div class="field">
					<label for="price4Up">Preis 4:</label>
					<input type="number" step="0.01" id="price4Up" name="price4">
				</div>
				<div class="field">
					<label for="price5Up">Preis 5:</label>
					<input type="number" step="0.01" id="price5Up" name="price5">
				</div>
				<div class="field">
					<label for="idCalendarUp">Kalender:</label>
					<select id="idCalendarUp" name="idCalendar" required>
					</select>
				</div>				
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateProductForm" class="btn btn-primary updateProduct">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal for choice of deleting product with active orders-->
	<div class="modal" id="deleteProductChoice" tabindex="-1" role="dialog" aria-labelledby="deleteProductChoiceLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="deleteProductChoiceLabel">Artikel l&ouml;schen</h4>
		  </div>
		  <div class="modal-body">
				<p>Der Artikel ist noch bestellt. Soll der Artikel mitsamt den betreffenden Bestellungen gelöscht werden?</p>
			</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<button type="button" class="btn btn-primary deleteProductAndOrdersButton">Ja</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/products.js"></script>
</html>




















