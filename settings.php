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
    <link href="external/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">


		<!--jquery files -->
		<link href="external/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
		<script src="external/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
		<script src="external/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
		<!--datepicker language-->
		<script src="external/jquery-ui-1.11.4.custom/datepicker-de.js"></script>

    <!-- Custom styles for this template -->
	<link href="css/brotportal.css" rel="stylesheet">
	<link href="css/settings.css" rel="stylesheet">

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
          <a class="navbar-brand" href="orders.php"><img class="brand-logo" src="images/Logo.png" alt="Joldelunder"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="orders.php">Bestellungen</a></li>
              <li><a href="info.php">Info</a></li>
              <li class="active"><a href="settings.php">Einstellungen</a></li>
              <li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
			<div class="row mainrow">
				<div class="col-md-3 col-sm-6">

				</div>
				<div class="col-md-3 col-sm-6 col-md-push-6">
				</div>
				<div class="col-md-6 col-sm-12 col-md-pull-3 main-content">
					<hr>
                    <div class="button_group handleData">
                        <button type="button" class="btn btn-primary updatePasswordButton">
                            Passwort ändern
                        </button>
                        <button type="button" class="btn btn-primary updateEMailButton" >
                            Einstellungen &auml;ndern
                        </button>
                    </div>
                    <h3>Ihre Daten und Einstellungen</h3>
                    <p>
                        Kunden Nr.: <span class="customerIDDisp"></span><br />
                        Name: <span class="nameDisp"></span><br />
                        E-Mail: <span class="mailAdressToDisp"></span><br />
                        E-Mail-Verteiler: <span class="mailAdressReceiveDisp"></span><br />
                        Automatisch Abschicken <a href="" title="Der Shop schickt die Bestellung 2 Minuten nach der letzten Änderung automatisch ab, wenn das Fenster offen gelassen wurde.">(?)</a>: <span class="autoSendOrdersDisp"></span><br />
                        Bestellwarnschwelle pro Artikel <a href="" title="Warnt Sie, wenn der Bestellwert eines Artikels höher ist als dieser Wert. Richtig eingestellt sollte es helfen, versehentliche Vertipper zu vermeiden.">(?)</a>: <span class="warningThresholdDisp"></span><br />
                    </p>
                </div>
				</div>
			</div>
    </div> <!-- /container -->
	

  </body>

  <!-- Modal -->
  <div class="modal" id="updateUserPassword" tabindex="-1" role="dialog" aria-labelledby="updateUserPwLabel">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="updateUserPwLabel">Passwort &auml;ndern</h4>
              </div>
              <div class="modal-body">
                  <form id="updateUserPasswordForm" method="post" action="ajax/settings_updatePassword.php">
                      <div class="field">
                          <label for="passwordOld">Altes Passwort:</label>
                          <input type="password" id="passwordOld" name="passwordOld" required>
                      </div>
                      <div class="field">
                          <label for="passwordNew1">Neues Passwort:</label>
                          <input type="password" id="passwordNew1" name="passwordNew1" required>
                      </div>
                      <div class="field">
                          <label for="passwordNew2">Neues Passwort (nocheinmal):</label>
                          <input type="password" id="passwordNew2" name="passwordNew2" required>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                  <button type="submit" form="updateUserPasswordForm" class="btn btn-primary updateUserPassword">&Auml;nderungen speichern</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal" id="updateUserMail" tabindex="-1" role="dialog" aria-labelledby="updateUserMailLabel">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="updateUserMailLabel">Einstellungen &auml;ndern</h4>
              </div>
              <div class="modal-body">
                  <form id="updateUserMailForm" method="post" action="ajax/settings_updateMail.php">
                      <div class="field">
                          <label for="passwordOld">Passwort:</label>
                          <input type="password" id="passwordOldMail" name="passwordOld" required>
                      </div>
                      <div class="field">
                         <label for="warningThresholdUp">Bestellwarnschwelle:</label>
                         <input type="number" id="warningThreshold" name="warningThreshold">
                      </div>
                      <div class="field">
                         <input type="hidden" name="autoSendOrders" value="0">
                      </div>
                      <div class="field">
                          <label for="autoSendOrdersUp">Automatisch Abschicken:</label>
                          <input type="checkbox" id="autoSendOrders" name="autoSendOrders" value="1">
                      </div>
                      <div class="field">
                          <label for="EMail">E-Mail (geschäftl.):</label>
                          <input type="email" id="EMail" name="mailAdressTo">
                      </div>
                      <div class="field">
                          <label for="MailingList">E-Mail Verteiler (falls Mehrere, bitte durch , trennen):</label>
                          <input type="email" id="MailingList" name="mailAdressReceive" style="width:100%;" multiple>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                  <button type="submit" form="updateUserMailForm" class="btn btn-primary updateUserMail">&Auml;nderungen speichern</button>
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
	<script src="js/settings.js"></script>
</html>




















