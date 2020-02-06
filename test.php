<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['agbsRead'] != 0) {
		echo "<script>window.location.href = 'orders.php';</script>";
}?>
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
    <link href="external/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
		<!--jquery files -->
		<link href="external/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="external/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="external/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
		<!--datepicker language-->
		<script src="external/jquery-ui-1.11.4.custom/datepicker-de.js"></script>
		<script src="external/threesixty-slider/src/threesixty.js"></script>
    <!-- Custom styles for this template -->
	<link href="css/login.css" rel="stylesheet">
	<link href="external/threesixty-slider/src/styles/threesixty.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
	
	
	<body>
		<div class="threesixty car">
			<div class="spinner">
					<span>0%</span>
			</div>
			<ol class="threesixty_images"></ol>
		</div>

	</body>
	<script>
		window.onload = init;

		var car;
		function init(){

				car = $('.car').ThreeSixty({
						totalFrames: 48, // Total no. of image you have for 360 slider
						endFrame: 48, // end frame for the auto spin animation
						currentFrame: 1, // This the start frame for auto spin
						imgList: '.threesixty_images', // selector for image list
						progress: '.spinner', // selector to show the loading progress
						imagePath:'external/threesixty-slider/assets/', // path of the image assets
						filePrefix: '', // file prefix if any
						ext: '.jpg', // extention for the assets
						height: 1000,
						width: 447,
						navigation: false,
						disableSpin: false
				});

		}
	</script>
	
		<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="external/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		
	<!-- Own js files-->
	<script src="js/brotportal.js"></script>
	<script src="js/login.js"></script>
</html>
