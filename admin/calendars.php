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
    <link href="../external/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
		<!--jquery files -->
		<link href="../external/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="../external/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="../external/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
		<!--datepicker language-->
		<script src="../external/jquery-ui-1.11.4.custom/datepicker-de.js"></script>

    <!-- Custom styles for this template -->
	<link href="css/admin.css" rel="stylesheet">
	<link href="css/calendar.css" rel="stylesheet">

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
								<li><a href="categories_prizes.php">Preiskategorien</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="categories_relations.php">Kunden - Produkte</a></li>
              </ul>
            </li>
						<li class="dropdown active">
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
					<h3>Kalender</h3>
					<ul class="sidebarList">
					</ul>
				</div>
				<div class="col-md-9 main-content">
					<div id="messages"></div>
					<div class="button_group handleData">
						<button type="button" class="btn btn-primary createCalendarButton">
						Kalender anlegen
						</button>
						<button type="button" class="btn btn-primary updateCalendarButton" >
						Kalender &auml;ndern
						</button>
						<button type="button" class="btn btn-primary deleteCalendarButton" >
						Kalender l&ouml;schen
						</button>
					</div> 
					<h1>Produkte mit diesem Kalender:</h1>
					<hr>
					<ul class="productList"></ul>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update row -->
	<div class="modal" id="createCalendar" tabindex="-1" role="dialog" aria-labelledby="createCalendarLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createCalendarLabel">Kalender hinzufügen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createCalendarForm" method="post" action="ajax/calendars_create.php">
				<div class="field">
					<label for="calendarName">Kalender:</label>
					<input id="calendarName" name="calendarName">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createCalendarForm" class="btn btn-primary createCalendar">Kalender speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateCalendar" tabindex="-1" role="dialog" aria-labelledby="updateCalendarLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateCalendarLabel">Kalender &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateCalendarForm" method="post" action="ajax/calendars_update.php">
				<div class="field">
					<label for="calendarNameUp">Kalender:</label>
					<input id="calendarNameUp" name="calendarName">
				</div>
				<div class="field">
					<input type="hidden" id="calendarIdUp" name="calendarId">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateCalendarForm" class="btn btn-primary updateCalendar">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../external/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/calendars.js"></script>
</html>




















