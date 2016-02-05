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
            <li class="dropdown active">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kategorien <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="categories_users.php">Kundenkategorien</a></li>
                <li><a href="categories_products.php">Produktkategorien</a></li>
								<li role="separator" class="divider"></li>
                <li><a href="customers-products.php">Kunden - Produkte</a></li>
              </ul>
            </li>
						<li><a href="#account">Konto</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
			<div class="row mainrow">
				<div class="col-md-3">
					<h3>Kundenkategorien</h3>
					<ul class="sidebarList">
					</ul>
				</div>
				<div class="col-md-9 main-content">
					<div id="messages"></div>
					<div class="button_group handleData">
						<button type="button" class="btn btn-primary createUserCatButton">
						Kundenkategorie anlegen
						</button>
						<button type="button" class="btn btn-primary updateUserCatButton" >
						Kundenkategorie &auml;ndern
						</button>
						<button type="button" class="btn btn-primary deleteUserCatButton" >
						Kundenkategorie l&ouml;schen
						</button>
					</div> 
					<h1>Kunden dieser Kategorie:</h1>
					<hr>
					<ul class="userList"></ul>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update row -->
	<div class="modal" id="createUserCat" tabindex="-1" role="dialog" aria-labelledby="createUserCatLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createUserCatLabel">Kundenkategorie hinzufügen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createUserCatForm" method="post" action="ajax/categories_user_create.php">
				<div class="field">
					<label for="userCatName">Kundenkategorie:</label>
					<input id="userCatName" name="userCatName">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createUserCatForm" class="btn btn-primary createUserCat">Kundenkategorie speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateUserCat" tabindex="-1" role="dialog" aria-labelledby="updateUserCatLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateUserCatLabel">Kundenkategorie &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateUserCatForm" method="post" action="ajax/categories_user_update.php">
				<div class="field">
					<label for="userCatNameUp">Kundenkategorie:</label>
					<input id="userCatNameUp" name="userCatName">
				</div>
				<div class="field">
					<input type="hidden" id="catIdUp" name="catId">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateUserCatForm" class="btn btn-primary updateUserCat">&Auml;nderungen speichern</button>
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
	<script src="js/categories_users.js"></script>
</html>




















