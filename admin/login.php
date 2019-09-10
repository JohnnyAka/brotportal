
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
	<link href="css/login.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>,
	
	<body>
	
	
	<div id="messages"></div>
	<div id="login-controls">
		<h1>Admin Login</h1>
		<form id="adminLoginForm" method="post" action="ajax/login_admin.php">
			<div class="field">
				<label for="adminName">Name:</label>
				<input id="adminName" name="adminName">
			</div>
			<div class="field">
				<label for="adminPassword">Passwort:</label>
				<input type="password" id="adminPassword" name="adminPassword">
			</div>
		</form>
		<div class="button_group handleData">
			<button type="submit" form="adminLoginForm" class="btn btn-primary adminLoginButton">
				Login
			</button>
		</div>
	</div> 
					
	</body>
	
	
		<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../external/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/login.js"></script>
</html>
