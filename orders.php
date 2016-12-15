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

		<?php
			require 'orders_functions.php';
		?>
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
				<div class="col-md-3 col-sm-6">
					<h3>Produktliste</h3>
					<ul class="sidebarList listsHeight">
						<?php
							include('queries/db_queries.php');
							$db = new db_connection();
							//get customerdata
							$customer = $db->getData("users",array("id","customerID","name","customerCategory"), "id=".$_SESSION['userid'])[0];
							//echo '<script>console.log('. json_encode(      ).')</script>';
							//get visible cats
							$visibleCats = $db->getData("categoryRelations",array("idProductCat"), "idUserCat=".$customer['customerCategory']);
							//get products and make products dictionary and category dictionary
							//produces a an associative array with key = product ID and objects with keys: "id","productID","name","productCategory","visibleForUser"
							//for category dict: associative array with key = category ID and value = name
							//example: $productDict['8']['name'] ; $categoryDict['5']
							$productDict = array(); $categoryDict = array();
							foreach($visibleCats as $category){
								$queryResult = $db->getData("products",array("id","productID","name","productCategory","visibleForUser"), "productCategory=".$category['idProductCat']);
								$arrayLength = count($queryResult);
								for($x=0; $x < $arrayLength; $x++){
									$productDict[$queryResult[$x]['id']] = $queryResult[$x];
								}
								$categoryDict[$category['idProductCat']] = $db->getData("productCategories",array("id","name"), "id=".$category['idProductCat'])[0]['name'];
							}
							uasort($categoryDict, function($a, $b){
								return strcasecmp($a,$b);
							});
							foreach($categoryDict as $catId => $catName){
								echo "<li class='sidebarElement showMultipleArticles' data-id=".$catId.">".$catName."</li>";
								echo '<ul class="subSidebarList">';
								$productsOfCategory = search($productDict, 'productCategory', $catId);
								usort($productsOfCategory, function($a, $b) {
									return strcasecmp($a['name'], $b['name']);
								});
								foreach($productsOfCategory as $product){
									echo "<li class='subSidebarElement showSingleArticle' data-id=".$product['id'].">".$product['name']."<button class='btn btn-default btn-xs buttonAddProduct' type='button'><span class='glyphicon glyphicon-triangle-right iconAddProduct' aria-hidden='true'></span></button></li>";
								}
								echo '</ul>';
							}
							
						?>
					</ul>
				</div>
				<div class="col-md-3 col-sm-6 col-md-push-6">
					<div class="productActionsWrapper">
						<div class="productActions">
							<!-- share the session variables with orders.js -->
							<?php
							echo '<input type="hidden" id="userID" data-value="'.$_SESSION["userid"].'">'
							?>
							<input type="text" id="ordersDatepicker">
							<button type="button" class="btn btn-primary sendOrderButton">
								abschicken
							</button>
							<button type="button" class="btn btn-primary deleteOrderButton">
								löschen
							</button>
							<span id="orderSentSign" class="glyphicon glyphicon-check" aria-hidden="true"></span>
							<!--glyphicon glyphicon-share
							glyphicon glyphicon-check
							-->
						</div>
					</div>
					<form id="sendOrderForm" class="listsHeight" method="post" action="ajax/orders_sendOrder.php">					
					</form>
				</div>
				<div class="col-md-6 col-sm-12 col-md-pull-3 main-content">
					<div id="messages"></div>
					<div class="productContent"></div>
					<hr>
				</div>
			</div>
    </div> <!-- /container -->


  </body>
  
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/orders.js"></script>
</html>




















