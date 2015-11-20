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

    <!-- Custom styles for this template -->
    <link href="navbar-fixed-top.css" rel="stylesheet">
	<link href="css/products.css" rel="stylesheet">

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
            <li class="active"><a href="products.php">Produkte</a></li>
            <li><a href="users.php">Benutzer</a></li>
            <li><a href="#orders">Bestellungen</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kategorien <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Kunden</a></li>
                <li><a href="#">Produkte</a></li>
				<li role="separator" class="divider"></li>
                <li><a href="#">Kunden - Produkte</a></li>
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
			<h3>Produkte</h3>
			<ul class="productList">
			  <?php
			    include('db_crud.php');
			    $db = new db_connection();
				$productList = $db->getData("products",array("id","name"));
				
				
			    foreach($productList as $item){
				  echo "<li data-id=".$item['id'].">".$item['name']."</li>";
				}	
				
			  ?>
			</ul>
	    </div>
	    <div class="col-md-9">
		<h1>Ausgew&auml;hlter Artikel</h1>
		  <p>
			Artikel Nr.: <a class="displayProductID"></a><br />
		    Name: <a class="displayName"></a><br />
			Beschreibung: <a class="displayDescription"></a><br /><br />
		  </p>
			<p>
			  <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
			</p>
		</div>
	  </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../bootstrap-3.3.5-dist/assets/js/ie10-viewport-bug-workaround.js"></script>
	<!-- Own js files-->
	<script src="js/products.js"></script>
  </body>
</html>




















