<?php
session_start();
if(!isset($_SESSION['userid'])) {
   die("Bitte erst einloggen");  
	 echo "<script>window.location.href = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">

    <title>Joldelunder Brotportal</title>

    <!-- Bootstrap core CSS -->
    <link href="external/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
		<!--jquery files -->
		<link href="external/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="external/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="external/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
		<!--datepicker language-->
		<script src="external/jquery-ui-1.11.4.custom/datepicker-de.js"></script>

		<?php
			require 'orders_functions.php';
		?>
    <!-- Custom styles for this template -->
	<link href="css/brotportal.css" rel="stylesheet">
	<link href="css/orders.css" rel="stylesheet">
	<link href="css/xxs-bootstrap.css" rel="stylesheet">

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
          <a class="navbar-brand" href="orders.php"><img class="brand-logo" src="images/small/Logo/Logo.png" alt="Joldelunder"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="orders.php">Bestellungen</a></li>
              <li><a href="info.php">Info</a></li>
              <li><a href="settings.php">Einstellungen</a></li>
			<li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
			<div class="row mainrow">
				<div class="col-md-3 col-sm-6">
					<form name="searchBoxForm" id="searchBoxForm" method="post" action="ajax/orders_searchProducts.php">
						<div class="input-group">
							<input type="text" class="form-control productSearchTextInput" id="productSearchTextInput" name="productSearchTextInput" placeholder="Suche...">
							<span class="input-group-btn">
								<button class="btn btn-default searchProductsButton" type="submit"><span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
							</span>
						</div>
					</form>
					<h3>Produktliste</h3>
					<div class="productList">
					</div>
					<p class="oekoControlNumber">DE-Öko-006</p>
				</div>
				<div class="col-md-3 col-sm-6 col-md-push-6 rightScreenOrderColumn">
					<hr class="controlsDivider">
					<!-- share the session variables with orders.js -->
					<?php
					echo '<input type="hidden" id="userID" data-value="'.$_SESSION["userid"].'">'
					?>
					<ul class="nav nav-tabs nav-tabs-ordermode">
					  <li role="presentation" class="orderTabMenuItemNormal active"><a href="#">Normal</a></li>
					  <li role="presentation" class="orderTabMenuItemStandard"><a href="#">Standard</a></li>
					</ul>
					<h3 id="sendOrdersText">Bestellung zum</h3>
					<input type="text" id="ordersDatepicker">
					<div class="standardOrderSlot">
						<span data-value="1" class="btn btn-xs standardOrder">1</span>
						<span data-value="2" class="btn btn-xs standardOrder">2</span>
						<span data-value="3" class="btn btn-xs standardOrder">3</span>
						<span data-value="4" class="btn btn-xs standardOrder">4</span>
						<span data-value="5" class="btn btn-xs standardOrder">5</span>
						<span data-value="6" class="btn btn-xs standardOrder">6</span>
						<span data-value="7" class="btn btn-xs standardOrder">7</span>
						<span data-value="8" class="btn btn-xs standardOrder">8</span>
						<span data-value="9" class="btn btn-xs standardOrder">9</span>
					</div>
					<button type="submit" form="sendOrderForm" class="btn btn-primary sendOrderButton">
						bestellen
					</button>
					<button type="button" class="btn btn-primary deleteOrderButton">
						löschen
					</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pickDateModal">
						Vortag/Standard
					</button>
					<span id="orderSentSign" class="btn glyphicon glyphicon-check sendOrderButton" aria-hidden="true"></span>
					<span id="sendListOptionsExpander" class=" glyphicon glyphicon-plus"></span>
					<div class="sendListOptions">
						Liste sortieren: 
						<span id="sortOrderListByAlphabet" class="btn btn-xs glyphicon glyphicon-sort-by-alphabet"></span>
						<span id="sortOrderListByProductId" class="btn btn-xs glyphicon glyphicon-sort-by-order"></span>
					</div>
					<hr class="orderListDivider">
					<form id="sendOrderForm" class="rightListHeight" method="post" action="ajax/orders_sendOrder.php">			
					</form>
					<p class="orderedProductsCounter"></p>
				</div>
				<div class="col-md-6 col-sm-12 col-md-pull-3 main-content">
					<hr class="productDivider">
					<div class="productContent"></div>
				</div>
			</div>
    </div> <!-- /container -->
	

  </body>

	<!-- Modal -->
	<div id="pickDateModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Bestellung übernehmen</h4>
				</div>
				<div class="modal-body">
					<p>Bitte wählen Sie den Tag oder den Standardspeicherplatz aus, von dem die Bestellung übernommen werden soll:</p>
					<input type="text" id="takeDatepicker">
					<div class="standardOrderTakeoverSlot">
					<p>Standardbestellungen</p>
						<span data-value="1" class="btn btn-xs standardOrderTakeover">1</span>
						<span data-value="2" class="btn btn-xs standardOrderTakeover">2</span>
						<span data-value="3" class="btn btn-xs standardOrderTakeover">3</span>
						<span data-value="4" class="btn btn-xs standardOrderTakeover">4</span>
						<span data-value="5" class="btn btn-xs standardOrderTakeover">5</span>
						<span data-value="6" class="btn btn-xs standardOrderTakeover">6</span>
						<span data-value="7" class="btn btn-xs standardOrderTakeover">7</span>
						<span data-value="8" class="btn btn-xs standardOrderTakeover">8</span>
						<span data-value="9" class="btn btn-xs standardOrderTakeover">9</span>
					</div>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default takeOrdersFromButton">Übernehmen</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Modal -->
	<div id="pickDateAlertModal" class="modal fade" role="dialog">
		<div class="modal-dialog" role="content">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Warnung</h4>
				</div>
				<div class="modal-body">
					<p>Bei einem Datumswechsel verfallen die Änderungen, die noch nicht abgeschickt wurden. Wollen Sie dennoch das Datum wechseln?</p>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default changeDateDespiteAlert">Ja</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Modal -->
	<div id="warningThresholdAlertModal" class="modal fade" role="dialog">
		<div class="modal-dialog" role="content">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Warnung</h4>
				</div>
				<div class="modal-body">
					<p id="warningThresholdAlertText"></p>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default violateWarningThresholdAlert">Ja</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Modal -->
  <div class="modal" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModal">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="alertMessageTitle">Nachricht</h4>
              </div>
              <div class="modal-body">
								<span id="alertMessageText">Text</span>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
              </div>
          </div>
      </div>
  </div>
	
	<!-- Modal -->
  <div class="modal" id="imgBigModal" tabindex="-1" role="dialog" aria-labelledby="imgBigModal">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="imgBigInModalTitle"></h4>
              </div>
              <div class="modal-body">
					<img id="productImgBigInModal">
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
              </div>
          </div>
      </div>
  </div>


	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="external/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/orders.js"></script>
</html>




















