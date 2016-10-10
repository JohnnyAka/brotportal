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
	<link href="css/categories_users.css" rel="stylesheet">

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
            <li ><a href="orders.php">Bestellungen</a></li>
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kategorien <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="categories_users.php">Kundenkategorien</a></li>
                <li><a href="categories_products.php">Produktkategorien</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="categories_relations.php">Kunden - Produkte</a></li>
              </ul>
            </li>
						<li class="active"><a href="settings.php">Einstellungen</a></li>
						<li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
			<div class="row mainrow">
				<div class="col-md-3">
				</div>
				<div class="col-md-9 main-content">
					<div id="messages"></div>
					
						<form id="updateSettingsForm" method="post" action="ajax/settings_update.php">
							<div class="field">
								<label for="adminName">Administrator Name:</label>
								<input id="adminName" name="adminName">
							</div>
							<div class="field">
								<label for="adminPassword">Administrator Passwort:</label>
								<input id="adminPassword" name="adminPassword">
							</div>
							<div class="field">
								<label for="deleteOrdersInDays">Speicherdauer der Bestellungen in Tagen:</label>
								<input id="deleteOrdersInDays" name="deleteOrdersInDays">
							</div>
							<div class="field">
								<label for="imagesPath">Bilderpfad:</label>
								<input id="imagesPath" name="imagesPath">
							</div>
							<div class="field">
								<label for="autoExportOrdersTime">Automatischer Export der Bestellungen (in hh:mm:ss):</label>
								<input id="autoExportOrdersTime" name="autoExportOrdersTime">
							</div>
							<div class="field">
								<label for="exportOrdersTo">Exportadresse:</label>
								<input id="exportOrdersTo" name="exportOrdersTo">
							</div>
							<div class="field">
								<label for="saveDatabaseTo">Datensicherungspfad:</label>
								<input id="saveDatabaseTo" name="saveDatabaseTo">
							</div>
						</form>
					<div class="button_group handleData">
						<button type="submit" form="updateSettingsForm" class="btn btn-primary updateSettingsButton">
							&Auml;nderungen speichern
						</button>
					</div> 
				</div>
			</div>
    </div> <!-- /container -->


  </body>
 
	
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/settings.js"></script>
</html>




















