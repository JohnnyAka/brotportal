<?php
session_start();
if(!isset($_SESSION['userid'])) {
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
    <link rel="icon" href="../favicon.ico">

    <title>Joldelunder Brotportal</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
		<!--jquery files -->
		<link href="../jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="../jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="../jquery-ui-1.11.4.custom/jquery-ui.js"></script>
		<!--datepicker language-->
		<script src="../jquery-ui-1.11.4.custom/datepicker-de.js"></script>

    <!-- Custom styles for this template -->
	<link href="css/brotportal.css" rel="stylesheet">
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
          <a class="navbar-brand" href="index.php">Joldelunder</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="orders.php">Bestellungen</a></li>
						<li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
			<div class="row mainrow">
				<div class="col-md-3">
					<h3>Produktliste</h3>
					<ul class="sidebarList">
						<?php
							include('queries/db_queries.php');
							$db = new db_connection();
							//get customercat
							$customer = $db->getData("users",array("id","name"), "id==".$_SESSION['userid']);
							//get visible cats
							//get products
							$sidebarList = $db->getData("products",array("id","name"));
						
							foreach($sidebarList as $item){
							echo "<li class='sidelist' data-id=".$item['id'].">".$item['name']."</li>";
						}?>
					</ul>
				</div>
				<div class="col-md-6 main-content">
					<div id="messages"></div>
					<h1>Produkte</h1>
					<hr>
					<ul class="productsList"></ul>
				</div>
				<div class="col-md-3">
					<h3>Bestellte Produkte</h3>
					<ul class="rightSidebarList">
					</ul>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
  <!-- Modals for create and update row -->
	<div class="modal" id="createProductCat" tabindex="-1" role="dialog" aria-labelledby="createProductCatLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="createProductCatLabel">Produktkategorie hinzufügen</h4>
		  </div>
		  <div class="modal-body">
			<form id="createProductCatForm" method="post" action="ajax/categories_product_create.php">
				<div class="field">
					<label for="productCatName">Produktkategorie:</label>
					<input id="productCatName" name="productCatName">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="createProductCatForm" class="btn btn-primary createProductCat">Produktkategorie speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
  <!-- Modal -->
	<div class="modal" id="updateProductCat" tabindex="-1" role="dialog" aria-labelledby="updateProductCatLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="updateProductCatLabel">Produktkategorie &auml;ndern</h4>
		  </div>
		  <div class="modal-body">
			<form id="updateProductCatForm" method="post" action="ajax/categories_product_update.php">
				<div class="field">
					<label for="productCatNameUp">Produktkategorie:</label>
					<input id="productCatNameUp" name="productCatName">
				</div>
				<div class="field">
					<input type="hidden" id="catIdUp" name="catId">
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			<button type="submit" form="updateProductCatForm" class="btn btn-primary updateProductCat">&Auml;nderungen speichern</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/orders.js"></script>
</html>




















