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
					<h3>Produktliste</h3>
					<ul class="sidebarList listsHeight">
						<?php
							include('admin/db_crud.php');
							$db = new db_connection();
							//get customerdata
							$customer = $db->getData("users",array("id","customerID","name","customerCategory"), "id=?1",$_SESSION['userid'])[0];
							//echo '<script>console.log('. json_encode(      ).')</script>';
							//get visible cats
							$visibleCats = $db->getData("categoryRelations",array("idProductCat"), "idUserCat=?1",$customer['customerCategory']);
							//get products and make products dictionary and category dictionary
							//produces a an associative array with key = product ID and objects with keys: "id","productID","name","productCategory","visibleForUser"
							//for category dict: associative array with key = category ID and value = name
							//example: $productDict['8']['name'] ; $categoryDict['5']
							$productDict = array(); $categoryNameDict = array(); $categoryOrderDict = array();
							foreach($visibleCats as $category){
								$queryResult = $db->getData("products",array("id","productID","name","productCategory", "orderPriority","visibleForUser"), "productCategory=?1 AND visibleForUser=?2",array($category['idProductCat'],1));
								$arrayLength = count($queryResult);
								for($x=0; $x < $arrayLength; $x++){
									$productDict[$queryResult[$x]['id']] = $queryResult[$x];
								}
								$categoryEntry = $db->getData("productCategories",array("id","name","orderPriority"), "id=?1",$category['idProductCat'])[0];
								$categoryNameDict[$category['idProductCat']] = $categoryEntry['name'];
								$categoryOrderDict[$category['idProductCat']] = $categoryEntry['orderPriority'];
							}
							uksort($categoryOrderDict, function($a, $b) use ($categoryOrderDict, $categoryNameDict){
								$x = intval($categoryOrderDict[$a]);
								$y = intval($categoryOrderDict[$b]);
								if($x == $y){
									return strcasecmp($categoryNameDict[$a],$categoryNameDict[$b]);
								}
								return ($x<$y)?-1:1;
							});
							foreach($categoryOrderDict as $catId => $orderPriority){
								$catName = $categoryNameDict[$catId];
								echo "<li class='sidebarElement showMultipleArticles product-list-toggle' data-id=".$catId.">".$catName."<span class='icon-list-collapse glyphicon glyphicon-collapse-down' aria-hidden=\"true\"></span></li>";
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
									echo "<li class='subSidebarElement showSingleArticle' data-id=".$product['id'].">".$product['name']."<button class='btn btn-default btn-xs buttonAddProduct' type='button'><span class='glyphicon glyphicon-triangle-right iconAddProduct' aria-hidden='true'></span></button></li>";
								}
								echo '</ul>';
							}
						?>
					</ul>
				</div>
				<div class="col-md-3 col-sm-6 col-md-push-6">
					<hr class="controlsDivider">
					<!-- share the session variables with orders.js -->
					<?php
					echo '<input type="hidden" id="userID" data-value="'.$_SESSION["userid"].'">'
					?>
					<h3>Bestellung</h3>
					<input type="text" id="ordersDatepicker">
					<button type="submit" form="sendOrderForm" class="btn btn-primary sendOrderButton">
						abschicken
					</button>
					<button type="button" class="btn btn-primary deleteOrderButton">
						löschen
					</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pickDateModal">
						übernehmen
					</button>
					<span id="orderSentSign" class="btn glyphicon glyphicon-check sendOrderButton" aria-hidden="true"></span>
					<!--glyphicon glyphicon-share
					glyphicon glyphicon-check
					-->
					<hr class="orderListDivider">
					<form id="sendOrderForm" class="rightListHeight" method="post" action="ajax/orders_sendOrder.php">					
					</form>
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
					<p>Bitte wählen Sie den Tag aus, von dem die Bestellung übernommen werden soll:</p>
					<input type="text" id="takeDatepicker">
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default takeOrdersFromButton">Übernehmen</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
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
	
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="external/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/orders.js"></script>
</html>




















