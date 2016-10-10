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
		<!--datepicker language-->
		<script src="../../jquery-ui-1.11.4.custom/datepicker-de.js"></script>

    <!-- Custom styles for this template -->
	<link href="css/admin.css" rel="stylesheet">
	<link href="css/orders.css" rel="stylesheet">

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
            <li><a href="users.php">Benutzer</a></li>
            <li class="active"><a href="orders.php">Bestellungen</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kategorien <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="categories_users.php">Kundenkategorien</a></li>
                <li><a href="categories_products.php">Produktkategorien</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="categories_relations.php">Kunden - Produkte</a></li>
              </ul>
            </li>
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
						<button type="button" class="btn btn-primary createOrderButton">
						Zeile anlegen
						</button>
						<button type="button" class="btn btn-primary updateOrderButton" >
						Zeile &auml;ndern
						</button>
						<button type="button" class="btn btn-primary deleteOrderButton" >
						Zeile l&ouml;schen
						</button>
					</div> 
					<h1>Bestellung</h1>
					<p>Datum: <input type="text" id="datepicker"></p>
					<hr>
					<ul class="orderList"></ul>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update row -->
	<div class="modal" id="createOrder" tabindex="-1" role="dialog" aria-labelledby="createOrderLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createOrderLabel">Bestellzeile hinzufügen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createOrderForm" method="post" action="ajax/orders_create.php">
				
				<div class="field">
					<select id="idProduct" name="idProduct" required>
					</select>
				</div>
				<div class="field">
					<label for="number">Anzahl:</label>
					<input type="number" id="number" name="number">
				</div>
				<div class="field">
					<label for="hook">Lieferung:</label>
					<select id="hook" name="hook" required>
						<option value="1" >Lieferung 1</option>
						<option value="2">Lieferung 2</option>
						<option value="3">Lieferung 3</option>
						<option value="4">Extra</option>
						<option value="5">Nachlieferung</option>
						<option value="6">Retour</option>
					</select>
				</div>
				<div class="field">
					<input type="hidden" name="important" value="0">
				</div>
				<div class="field">
					<label for="important">Wichtig:</label>
					<input type="checkbox" id="important" name="important" value="1">
				</div>
				<div class="field">
					<label for="noteDelivery">Notiz (Lieferschein):</label>
					<textarea id="noteDelivery" name="noteDelivery"></textarea>
				</div>
				<div class="field">
					<label for="noteBaking">Notiz (Backzettel):</label>
					<textarea id="noteBaking" name="noteBaking"></textarea>
				</div>
				<div class="field">
					<input type="hidden" id="idCustomer" name="idCustomer">
				</div>
				<div class="field">
					<input type="hidden" id="orderDate" name="orderDate">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createOrderForm" class="btn btn-primary createOrder">Bestellzeile speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateOrder" tabindex="-1" role="dialog" aria-labelledby="updateOrderLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateOrderLabel">Artikel &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateOrderForm" method="post" action="ajax/orders_update.php">
				<div class="field">
					<label for="nameProductUp">Artikel:</label>
					<input id="nameProductUp" name="nameProduct" disabled="disabled">
				</div>
				<div class="field">
					<label for="delivery">Lieferung:</label>
					<input id="deliveryUp" name="delivery" disabled="disabled">
				</div>
				<div class="field">
					<label for="number">Anzahl:</label>
					<input type="number" id="numberUp" name="number">
				</div>
				<div class="field">
					<input type="hidden" name="important" value="0">
				</div>
				<div class="field">
					<label for="important">Wichtig:</label>
					<input type="checkbox" id="importantUp" name="important" value="1">
				</div>
				<div class="field">
					<label for="noteDelivery">Notiz (Lieferschein):</label>
					<textarea id="noteDeliveryUp" name="noteDelivery"></textarea>
				</div>
				<div class="field">
					<label for="noteBaking">Notiz (Backzettel):</label>
					<textarea id="noteBakingUp" name="noteBaking"></textarea>
				</div>
				<div class="field">
					<input type="hidden" id="idProductUp" name="idProduct" >
				</div>
				<div class="field">
					<input type="hidden" id="idCustomerUp" name="idCustomer">
				</div>
				<div class="field">
					<input type="hidden" id="hookUp" name="hook">
				</div>
				<div class="field">
					<input type="hidden" id="orderDateUp" name="orderDate">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateOrderForm" class="btn btn-primary updateOrder">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/orders.js"></script>
</html>




















