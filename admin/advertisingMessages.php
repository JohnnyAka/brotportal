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
	<link href="css/categories_products.css" rel="stylesheet">

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
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nachrichten <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="advertisingMessages.php">Nachrichten</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="advertisingMessages_relations.php">Nachrichten - Kunden</a></li>
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
					<h3>Nachrichten</h3>
					<ul class="sidebarList">
					</ul>
				</div>
				<div class="col-md-9 main-content">
					<div id="messages"></div>
					<div class="button_group handleData">
						<button type="button" class="btn btn-primary createAdvertisingMessageButton">
						Nachricht anlegen
						</button>
						<button type="button" class="btn btn-primary updateAdvertisingMessageButton" >
						Nachricht &auml;ndern
						</button>
						<button type="button" class="btn btn-primary deleteAdvertisingMessageButton" >
						Nachricht l&ouml;schen
						</button>
						<button type="button" class="btn btn-primary imageUploadAdButton" >
						Bilder hochladen
					  	</button>
					  	<button type="button" class="btn btn-primary showAdvertisingMessageButton" >
						Nachricht anzeigen
					  	</button>
					</div> 
					<h1>Ausgewählte Nachricht:</h1>
					<hr>
					<p> 
					 	Name: <span class="displayName"></span><br />
						Bild: <span class="displayImage"></span><br />
						&Uuml;berschrift: <span class="displayCaption"></span><br />
						Text: <span class="displayText"></span><br />
						Verlinktes Produkt: <span class="displayLinkedProduct"></span><br />
						Anzeige-Priorit&auml;t: <span class="displayPriority"></span><br />
						Popup Start: <span class="displayPopupStart"></span><br />
						Popup Ende: <span class="displayPopupEnd"></span><br />
						Messagebox Start: <span class="displayMessageboxStart"></span><br />
						Messagebox Ende: <span class="displayMessageboxEnd"></span><br />
					</p>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update row -->
	<div class="modal" id="createAdvertisingMessage" tabindex="-1" role="dialog" aria-labelledby="createAdvertisingMessageLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createAdvertisingMessageLabel">Nachricht hinzufügen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createAdvertisingMessageForm" method="post" action="ajax/advertisingMessages_create.php">
				<div class="field">
					<label for="name">Name:</label>
					<input id="name" name="name" required>
				</div>
				<div class="field">
					<label for="imageDirectory">Bild-Ordner:</label>
					<select id="imageDirectory" name="imageDirectory">
					</select>
				</div>
				<div class="field">
					<label for="messageImage">Bild:</label>
					<select id="messageImage" name="messageImage">
					</select>
				</div>
				<div class="field">
					<label for="messageHeader">Überschrift:</label>
					<input type="text" id="messageHeader" name="messageHeader">
				</div>
				<div class="field">
					<label for="messageText">Text:</label>
					<textarea id="messageText" name="messageText"></textarea>
				</div>
				<div class="field">
					<label for="popupStartDate">Popup-Startdatum:</label>
					<input type="text" id="popupStartDate" name="popupStartDate">
				</div>
				<div class="field">
					<label for="popupEndDate">Popup-Enddatum:</label>
					<input type="text" id="popupEndDate" name="popupEndDate">
				</div>
				<div class="field">
					<label for="messageboxStartDate">Messagebox-Startdatum:</label>
					<input type="text" id="messageboxStartDate" name="messageboxStartDate">
				</div>
				<div class="field">
					<label for="messageboxEndDate">Messagebox-Enddatum:</label>
					<input type="text" id="messageboxEndDate" name="messageboxEndDate">
				</div>
				<div class="field">
					<label for="linkedProductId">Angezeigtes Produkt:</label>
					<select id="linkedProductId" name="linkedProductId">
					</select>
				</div>
				<div class="field">
					<label for="orderPriority">Anzeigepriorität:</label>
					<input type="number" min="1" max="99" value="50" id="orderPriority" name="orderPriority">
				</div>


				<!--<div class="field">
					<label for="messageText2">Absatz 2:</label>
					<textarea id="messageText2" name="messageText2"></textarea>
				</div>
				<div class="field">
					<label for="messageText3">Absatz 3:</label>
					<textarea id="messageText3" name="messageText3"></textarea>
				</div>
				<div class="field">
					<label for="messageGreetings">Grußformel:</label>
					<input type="text" id="messageGreetings" name="messageGreetings">
				</div>
				<div class="field">
					<label for="messageFooter">Fußzeile:</label>
					<textarea id="messageFooter" name="messageFooter"></textarea>
				</div>-->

			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createAdvertisingMessageForm" class="btn btn-primary createAdvertisingMessage">Nachricht speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateAdvertisingMessage" tabindex="-1" role="dialog" aria-labelledby="updateAdvertisingMessageLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateAdvertisingMessageLabel">Nachricht &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateAdvertisingMessageForm" method="post" action="ajax/advertisingMessages_update.php">
				<div class="field">
					<label for="nameUp">Name:</label>
					<input id="nameUp" name="nameUp" required>
				</div>
				<div class="field">
					<label for="imageDirectoryUp">Bild-Ordner:</label>
					<select id="imageDirectoryUp" name="imageDirectoryUp">
					</select>
				</div>
				<div class="field">
					<label for="messageImageUp">Bild:</label>
					<select id="messageImageUp" name="messageImageUp">
					</select>
				</div>
				<div class="field">
					<input type="hidden" id="idUp" name="idUp">
				</div>
				<div class="field">
					<label for="messageHeaderUp">Überschrift:</label>
					<input type="text" id="messageHeaderUp" name="messageHeaderUp">
				</div>
				<div class="field">
					<label for="messageTextUp">Text:</label>
					<textarea id="messageTextUp" name="messageTextUp"></textarea>
				</div>
				<div class="field">
					<label for="popupStartDateUp">Popup-Startdatum:</label>
					<input type="text" id="popupStartDateUp" name="popupStartDateUp">
				</div>
				<div class="field">
					<label for="popupEndDateUp">Popup-Enddatum:</label>
					<input type="text" id="popupEndDateUp" name="popupEndDateUp">
				</div>
				<div class="field">
					<label for="messageboxStartDateUp">Messagebox-Startdatum:</label>
					<input type="text" id="messageboxStartDateUp" name="messageboxStartDateUp">
				</div>
				<div class="field">
					<label for="messageboxEndDateUp">Messagebox-Enddatum:</label>
					<input type="text" id="messageboxEndDateUp" name="messageboxEndDateUp">
				</div>
				<div class="field">
					<label for="linkedProductIdUp">Angezeigtes Produkt:</label>
					<select id="linkedProductIdUp" name="linkedProductIdUp">
					</select>
				</div>
				<div class="field">
					<label for="orderPriorityUp">Anzeigepriorität:</label>
					<input type="number" min="1" max="99" value="50" id="orderPriorityUp" name="orderPriorityUp">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateAdvertisingMessageForm" class="btn btn-primary updateAdvertisingMessage">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>

		<!-- Modal for image uploads-->
	<div class="modal" id="imageUpload" tabindex="-1" role="dialog" aria-labelledby="imageUploadLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="imageUploadLabel">Bilder hochladen</h4>
		  </div>
		  <div class="modal-body">
		  	<p>Bitte wählen Sie die Bilder aus, die Sie hochladen wollen:</p>
			<form id="uploadImagesForm" method="post" action="ajax/advertisingMessages_imageUpload.php">
				<div class="field">
					<label for="directoryInput">Speichern in:</label>
					<select id="directoryInput" name="directoryInput" required></select>
				</div>
				<div class="field">
					<label for="imageUploadInput"></label>
					<input type="file" id="imageUploadInput" name="imageUploadInput" accept="image/*" multiple required>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="uploadImagesForm" class="btn btn-primary imageUploadSubmitButton">Ok</button>
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
	<script src="js/advertisingMessages.js"></script>
</html>




















