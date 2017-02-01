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
		
    <!-- Custom styles for this template -->
	<link href="css/admin.css" rel="stylesheet">

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
            <li><a href="products.php">Produkte</a></li>
            <li class="active"><a href="users.php">Benutzer</a></li>
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
			<h3>Benutzer</h3>
			<ul class="sidebarList">
			  <?php
			    include('db_crud.php');
			    $db = new db_connection();
				$sidebarList = $db->getData("users",array("id","name"));
				
				
			    foreach($sidebarList as $item){
				  echo "<li class='sidelist' data-id=".$item['id'].">".$item['name']."</li>";
				}	
				
			  ?>
			</ul>
	    </div>
	    <div class="col-md-9 main-content">
		<div id="messages"></div>
		<div class="button_group handleData">
		  <button type="button" class="btn btn-primary createUserButton">
			Benutzer anlegen
		  </button>
		  <button type="button" class="btn btn-primary updateUserButton" >
			Benutzer &auml;ndern
		  </button>
		  <button type="button" class="btn btn-primary deleteUserButton" >
			Benutzer l&ouml;schen
		  </button>
		</div> 
		<h1>Ausgew&auml;hlter Benutzer</h1>
		  <p>
			Kunden Nr.: <span class="customerIDDisp"></span><br />
			Vorbestell-Kundennummer: <span class="preOrderCustomerIdDisp"></span><br />
		  Name: <span class="nameDisp"></span><br />
			Passwort: <span class="passwordDisp"></span><br />
			Kategorie: <span class="customerCategoryDisp"></span><br />
			Preiskategorie: <span class="priceCategoryDisp"></span><br />
			E-Mail: <span class="mailAdressToDisp"></span><br />
			E-Mail-Verteiler: <span class="mailAdressReceiveDisp"></span><br />
			Telefon Laden: <span class="telephone1Disp"></span><br />
			Telefon Ansprechpartner: <span class="telephone2Disp"></span><br />
			Fax: <span class="faxDisp"></span><br />
			Lieferadresse: <span class="storeAdressDisp"></span><br />
			Lieferdetails: <span class="whereToPutOrderDisp"></span><br />
		  </p>
		</div>
	  </div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update user -->
	<div class="modal" id="createUser" tabindex="-1" role="dialog" aria-labelledby="createUserLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createUserLabel">Benutzer anlegen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createUserForm" method="post" action="ajax/users_create.php">
				<div class="field">
					<label for="customerid">Kunden Nr.:</label>
					<input type="text" id="customerid" name="customerid" required>
				</div>
				<div class="field">
					<label for="preOrderCustomerId">Vorbestell-Kundennummer:</label>
					<input type="text" id="preOrderCustomerId" name="preOrderCustomerId">
				</div>
				<div class="field">
					<label for="name">Name:</label>
					<input type="text" id="name" name="name" required>
				</div>
				<div class="field">
					<label for="password">Passwort:</label>
					<input type="password" id="password" name="password">
				</div>
				<div class="field">
					<label for="customerCategory">Kunden-Kategorie:</label>
					<select id="customerCategory" name="customerCategory">
					</select>
				</div>
				<div class="field">
					<label for="priceCategory">Preiskategory:</label>
					<select id="priceCategory" name="priceCategory">
						<option value=0>Preis wird nicht angezeigt</option>
						<option value=1>Preis 1</option>
						<option value=2>Preis 2</option>
						<option value=3>Preis 3</option>
						<option value=4>Preis 4</option>
						<option value=5>Preis 5</option>
					</select>
				</div>
				<div class="field">
					<label for="mailAdressTo">E-Mail Adresse:</label>
					<input type="email" id="mailAdressTo" name="mailAdressTo">
				</div>
				<div class="field">
					<label for="mailAdressReceive">E-Mail Adresse (Verteiler):</label>
					<input type="email" id="mailAdressReceive" name="mailAdressReceive">
				</div>
				<div class="field">
					<label for="telephone1">Telefon (Laden):</label>
					<input type="tel" id="telephone1" name="telephone1">
				</div>
				<div class="field">
					<label for="telephone2">Telefon (Zuständiger):</label>
					<input type="tel" id="telephone2" name="telephone2">
				</div>
				<div class="field">
					<label for="fax">Fax:</label>
					<input type="tel" id="fax" name="fax">
				</div>
				<div class="field">
					<label for="storeAdress">Lieferadresse:</label>
					<textarea type="text" id="storeAdress" name="storeAdress"></textarea>
				</div>
				<div class="field">
					<label for="whereToPutOrder">Lieferdetails:</label>
					<textarea id="whereToPutOrder" name="whereToPutOrder"></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createUserForm" class="btn btn-primary createUser">Benutzer speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateUser" tabindex="-1" role="dialog" aria-labelledby="updateUserLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateUserLabel">Benutzer &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateUserForm" method="post" action="ajax/users_update.php">
				<div class="field">
					<label for="customeridUp">Kunden Nr.:</label>
					<input type="text" id="customeridUp" name="customerid" required>
				</div>
				<div class="field">
					<label for="preOrderCustomerIdUp">Vorbestell-Kundennummer:</label>
					<input type="text" id="preOrderCustomerIdUp" name="preOrderCustomerId">
				</div>
				<div class="field">
					<input type="hidden" id="idUp" name="id">
				</div>
				<div class="field">
					<label for="nameUp">Name:</label>
					<input type="text" id="nameUp" name="name">
				</div>
				<div class="field">
					<label for="passwordUp">Passwort:</label>
					<input type="text" id="passwordUp" name="password">
				</div>
				<div class="field">
					<label for="customerCategoryUp">Kunden-Kategorie:</label>
					<select id="customerCategoryUp" name="customerCategory">
					</select>
				</div>
				<div class="field">
					<label for="priceCategoryUp">Preiskategory:</label>
					<select id="priceCategoryUp" name="priceCategory">
						<option value=0>Preis wird nicht angezeigt</option>
						<option value=1>Preis 1</option>
						<option value=2>Preis 2</option>
						<option value=3>Preis 3</option>
						<option value=4>Preis 4</option>
						<option value=5>Preis 5</option>
					</select>
				</div>
				<div class="field">
					<label for="mailAdressToUp">E-Mail Adresse:</label>
					<input type="email" id="mailAdressToUp" name="mailAdressTo">
				</div>
				<div class="field">
					<label for="mailAdressReceiveUp">E-Mail Adresse (Verteiler):</label>
					<input type="email" id="mailAdressReceiveUp" name="mailAdressReceive">
				</div>
				<div class="field">
					<label for="telephone1Up">Telefon (Laden):</label>
					<input type="tel" id="telephone1Up" name="telephone1">
				</div>
				<div class="field">
					<label for="telephone2Up">Telefon (Zuständiger):</label>
					<input type="tel" id="telephone2Up" name="telephone2">
				</div>
				<div class="field">
					<label for="faxUp">Fax:</label>
					<input type="tel" id="faxUp" name="fax">
				</div>
				<div class="field">
					<label for="storeAdressUp">Lieferadresse:</label>
					<textarea id="storeAdressUp" name="storeAdress"></textarea>
				</div>
				<div class="field">
					<label for="whereToPutOrderUp">Lieferdetails:</label>
					<textarea id="whereToPutOrderUp" name="whereToPutOrder"></textarea>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateUserForm" class="btn btn-primary updateUser">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
	 <!-- Modal for choice of deleting user with active orders-->
	<div class="modal" id="deleteUserChoice" tabindex="-1" role="dialog" aria-labelledby="deleteUserChoiceLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="deleteUserChoiceLabel">Benutzer l&ouml;schen</h4>
		  </div>
		  <div class="modal-body">
				<p>Der Benutzer hat noch Bestellungen offen. Soll der Benutzer mitsamt den betreffenden Bestellungen gelöscht werden?</p>
			</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<button type="button" class="btn btn-primary deleteUserAndOrdersButton">Ja</button>
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
	<script src="js/users.js"></script>
</html>




















