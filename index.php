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

    <!-- Custom styles for this template -->
	<link href="css/login.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
	
	
	<body>
	
	
		<div id="login-controls">
			<h1>Login</h1>
			<form id="loginForm" method="post" action="ajax/login.php">
				<div class="field">
					<label for="name">Kundennummer:</label>
					<input id="name" class="controls" name="name">
				</div>
				<div class="field">
					<label for="password">Passwort:</label>
					<input type="password" id="password" class="controls" name="password">
				</div>
			</form>
			<div class="button_group handleData">
				<button type="submit" form="loginForm" class="btn btn-primary loginButton">
					Login
				</button>
			</div> 
		</div> 
	</body>
	
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
                  <button type="button" class="btn btn-default controls" data-dismiss="modal">Ok</button>
              </div>
          </div>
      </div>
  </div>
	
	<!-- Modal -->
  <div class="modal" id="agbModal" tabindex="-1" role="dialog" aria-labelledby="agbModal">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="agbMessageTitle">AGBs und Cookies</h4>
              </div>
              <div class="modal-body">
								<span id="agbMessageText">
									<p style="font-weight: bold">Bitte scrollen Sie bis ganz nach unten, um die AGBs zu bestätigen. Sie finden die AGBs außerdem unter dem Menüpunkt Info als Download.</p>								
									<h4>AGB vom Joldelunder B2B Online-Shop</h4>
									<p>
										<br>1. Geltungsbereich und Anbieter
										<br>(1) Diese Allgemeinen Geschäftsbedingungen gelten für alle Bestellungen, die Kunden bei dem Online-Shop der Bäckerei Lorenzen GmbH, Norderweg 7, 25862 Joldelund tätigen.
										<br>(2) Das Warenangebot in unserem Online-Shop richtet sich ausschließlich an Kunden, die als Unternehmer im Sinne von § 14 Abs. 1 BGB anzusehen sind, also bei Abschluss des Vertrages in Ausübung seiner gewerblichen oder selbstständigen beruflichen Tätigkeit handelt.
										<br>(3) Unsere Lieferungen, Leistungen und Angebote erfolgen ausschließlich auf der Grundlage dieser Allgemeinen Geschäftsbedingungen. Die Allgemeinen Geschäftsbedingungen gelten auch für alle künftigen Geschäftsbeziehungen, auch wenn sie nicht nochmals ausdrücklich vereinbart werden. Der Einbeziehung von Allgemeinen Geschäftsbedingungen des Kunden, die unseren Allgemeinen Geschäftsbedingungen widersprechen, wird schon jetzt widersprochen.
									</p>
									<p>
										<br>2. Vertragsschluss und Preise
										<br>(1) Mit Abschluss der Bestellung des Kunden in unserem Online-Shop gibt der Kunde ein verbindliches Kaufangebot ab. Soweit wir anschließend eine automatisierte Eingangsbestätigung versenden, stellt dies noch keine Annahme des Kaufangebots des Kunden dar. Ein Kaufvertrag über die Ware kommt erst zustande, wenn wir ausdrücklich die Annahme des Kaufangebots erklären oder wenn wir die Ware ohne vorherige ausdrückliche Annahmeerklärung aussondern und an den Kunden versenden.
										<br>(2) Die in unserem Online-Shop angegebenen Preise sind Netto-Preise, Brutto-Preise oder Verkaufsempfehlungen, je nachdem, wie es angemerkt ist. Bei Netto-Preisen tritt die jeweils gültige gesetzliche Mehrwertsteuer hinzu. Bei Brutto-Preisen und Verkaufsempfehlungen ist die Mehrwertsteuer enthalten. Dort wird der gegebenenfalls festgelegte Rabatt nach Abzug der Mehrwertsteuer abgezogen.
										<br>(3) Alle Preise verstehen sich zuzüglich der jeweils angegebenen Versandkosten, wenn Versandkosten vereinbart wurden.
										<br>3. Zahlungsbedingungen; Verzug
										<br>(1) In unserem Online-Shop wird entweder per Rechung oder per Sepa-Lastschrifteinzug gezahlt.
										<br>(2) Alle Zahlungen sind innerhalb von 15 Tagen nach Rechnungstellung zu leisten.
										<br>(3) Bei Zahlung per Lastschrift hat der Kunde ggf. die Kosten zu tragen, die infolge einer Rückbuchung einer Zahlungstransaktion mangels Kontodeckung oder aufgrund vom Kunden falsch übermittelter Daten der Bankverbindung entstehen.
										<br>(4) Gerät der Kunde mit einer Zahlung in Verzug, ist er zur Zahlung der gesetzlichen Verzugszinsen in Höhe von 9 Prozentpunkten über dem Basiszinssatz verpflichtet. Außerdem besteht ein Anspruch auf Zahlung einer Pauschale in Höhe von 40 Euro. Die Geltendmachung weiteren Schadensersatzes bleibt vorbehalten.
										<br>(5) Wenn der Kunde seinen Zahlungsverpflichtungen nicht pünktlich nachkommt oder sich herausstellt, dass seine finanziellen Verhältnisse für eine etwa erfolgte Kreditgewährung oder Stundung nicht mehr genügen, sind wir berechtigt, alle offenen Forderungen sofort fällig zu stellen oder Sicherheitsleistung zu verlangen.
									</p>
									<p>
										<br>4. Aufrechnung/Zurückbehaltungsrecht
										<br>(1) Ein Recht zur Aufrechnung steht dem Kunden nur dann zu, wenn seine Gegenforderung rechtskräftig festgestellt worden ist oder von uns nicht bestritten wird.
										<br>(2) Der Kunde kann ein Zurückbehaltungsrecht nur geltend machen, soweit seine Gegenforderung auf demselben Vertragsverhältnis beruht.
										<br>(3) Wir können ein Zurückbehaltungsrecht gegenüber allen künftigen, auch anerkannten Bestellungen des Kunden geltend machen, wenn der Kunde seinen Zahlungspflichten nicht nachkommt.
										<br>5. Lieferung, Transportgefahr, Liefertermine
										<br>(1) Sofern nicht anders vereinbart, erfolgt die Lieferung der Ware auf Ihren Wunsch von unserem Lager an die von Ihnen angegebene Adresse. Die Lieferung von Speditionsware erfolgt frei Bordsteinkante, soweit im Einzelfall nichts anderes vereinbart ist.
										<br>(2) Die Gefahr geht mit der Übergabe der Ware an den Spediteur, Frachtführer oder sonst zur Ausführung der Versendung bestellten Dritten auf den Kunden über. Die Übergabe beginnt zeitgleich mit dem Verladevorgang. Ein Annahmeverzug des Kunden führt zum Gefahrübergang.
										<br>(3) Wir werden von unserer Leistung frei, soweit wir im Rahmen eines kongruenten Deckungsgeschäfts von unseren Zulieferern selbst nicht rechtzeitig beliefert wurden, es sei denn, wir haben die Nichtlieferung selbst zu vertreten. Der Kunde wird über die fehlende Belieferung unverzüglich informiert und die Gegenleistung unverzüglich erstattet.
									</p>
									<p>
										<br>5. Eigentumsvorbehalt
										<br>(1) Wir behalten uns das Eigentum an der Ware bis zum vollständigen Ausgleich aller Forderungen aus der laufenden Geschäftsbeziehung vor. Vor Übergang des Eigentums an der Vorbehaltsware ist eine Verpfändung oder Sicherheitsübereignung nicht zulässig.
										<br>(2) Der Kunde darf die Ware im ordentlichen Geschäftsgang weiterverkaufen. Für diesen Fall tritt der Kunde bereits jetzt alle Forderungen in Höhe des Rechnungsbetrages, die dem Kunden aus dem Weiterverkauf erwachsen, an uns ab. Wir nehmen die Abtretung an. Der Kunde bleibt jedoch zur Einziehung der Forderungen ermächtigt. Soweit der Kunde seinen Zahlungsverpflichtungen nicht ordnungsgemäß nachkommt, behalten wir uns das Recht vor, Forderungen selbst einzuziehen.
										<br>(3) Bei Verbindung und Vermischung der Vorbehaltsware erwerben wir Miteigentum an der neuen Sache im Verhältnis des Rechnungswertes der Vorbehaltsware zu den anderen verarbeiteten Gegenständen zum Zeitpunkt der Verarbeitung.
										<br>(4) Wir verpflichten uns, die uns zustehenden Sicherheiten auf Verlangen insoweit freizugeben, als der realisierbare Wert unserer Sicherheiten die zu sichernden Forderungen um mehr als 10 % übersteigt. Die Auswahl der freizugebenden Sicherheiten obliegt uns.
									</p>
									<p>6. Gewährleistung
										<br>(1) Soweit nicht ausdrücklich etwas anderes vereinbart ist, richten sich die Gewährleistungsansprüche des Kunden nach den gesetzlichen Bestimmungen des Kaufrechts (§§ 433 ff. BGB) mit den in den folgenden Absätzen bestimmten Modifikationen.
										<br>(2) Für die Beschaffenheit der Ware sind nur unsere eigenen Angaben und die Produktbeschreibung des Herstellers verbindlich, nicht jedoch öffentliche Anpreisungen und Äußerungen und sonstige Werbung des Herstellers. Muster, Materialbeschaffenheiten und Struktur der Produkte können von den Angaben im Online-Shop abweichen. Unsere Angaben zum Gegenstand der Lieferung oder der Leistung einschließlich der Abbildungen sind nur annähernde Beschreibungen, soweit nicht für den vertraglichen Zweck eine genaue Übereinstimmung erforderlich ist.
										<br>(3) Sie sind verpflichtet, die Ware mit der gebotenen Sorgfalt auf Qualitäts- und Mengenabweichungen zu untersuchen und uns offensichtliche Mängel unverzüglich nach Empfang der Ware anzuzeigen. Dies gilt auch für später festgestellte verdeckte Mängel ab Entdeckung. Bei Verletzung der Untersuchungs- und Rügepflicht ist die Geltendmachung der Gewährleistungsansprüche ausgeschlossen.
										<br>(4) Bei Mängeln leisten wir nach unserer Wahl Gewähr durch Nachbesserung oder Ersatzlieferung (Nacherfüllung). Im Falle der Nachbesserung müssen wir nicht die erhöhten Kosten tragen, die durch die Verbringung der Ware an einen anderen Ort als den Erfüllungsort entstehen, sofern die Verbringung nicht dem bestimmungsgemäßen Gebrauch der Ware entspricht.
										<br>(5) Schlägt die Nacherfüllung zweimal fehl, kann der Kunde nach seiner Wahl Minderung verlangen oder vom Vertrag zurücktreten.
										<br>(6) Die Gewährleistungsfrist beträgt ein Jahr ab Ablieferung der Ware. Diese Beschränkung gilt nicht für Ansprüche aufgrund von Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit oder aus der Verletzung einer wesentlichen Vertragspflicht, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung der Vertragspartner regelmäßig vertrauen darf (Kardinalpflicht) sowie für Ansprüche aufgrund von sonstigen Schäden, die auf einer vorsätzlichen oder grob fahrlässigen Pflichtverletzung von uns oder unseren Erfüllungsgehilfen beruhen.
										<br>(7) Sollte im Einzelfall die Lieferung gebrauchter Produkte zwischen uns und dem Kunden vereinbart werden, geschieht dies unter Ausschluss jeglicher Gewährleistung.
										<br>
										<br>7. Haftung
										<br>(1) Unbeschränkte Haftung: Wir haften unbeschränkt für Vorsatz und grobe Fahrlässigkeit sowie nach Maßgabe des Produkthaftungsgesetzes. Für leichte Fahrlässigkeit haften wir bei Schäden aus der Verletzung des Lebens, des Körpers und der Gesundheit von Personen.
										<br>(2) Im Übrigen gilt folgende beschränkte Haftung: Bei leichter Fahrlässigkeit haften wir nur im Falle der Verletzung einer wesentlichen Vertragspflicht, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung Sie regelmäßig vertrauen dürfen (Kardinalpflicht). Die Haftung für leichte Fahrlässigkeit ist der Höhe nach beschränkt auf die bei Vertragsschluss vorhersehbaren Schäden, mit deren Entstehung typischerweise gerechnet werden muss. Diese Haftungsbeschränkung gilt auch zugunsten unserer Erfüllungsgehilfen.
									</p>
									<p>
										<br>8. Schlussbestimmungen
										<br>(1) Sollten eine oder mehrere Bestimmungen dieser AGB unwirksam sein oder werden, wird dadurch die Wirksamkeit der anderen Bestimmungen im Übrigen nicht berührt.
										<br>(2) Auf Verträge zwischen uns und Ihnen ist ausschließlich deutsches Recht anwendbar unter Ausschluss der Bestimmungen der United Nations Convention on Contracts for the International Sale of Goods (CISG, „UNKaufrecht“).
										<br>(3) Sind Sie Kaufmann, juristische Person des öffentlichen Rechts oder öffentlich-rechtliches Sondervermögen, so gilt Gerichtsstand für alle Streitigkeiten aus oder im Zusammenhang mit Verträgen zwischen uns und den Kunden an unserem Geschäftssitz.
									</p>
								</span>
              </div>
              <div class="modal-footer agbModal-footer">
									<p style="text-align:center; font-weight: bold">Bitte stimmen Sie beiden Punkten zu und klicken auf Ok, um fortzufahren</p>
									<input type="checkbox" class="agbModalCheck checkAGB" name="AGBs" value="1"> Ich stimme den AGBs dieser Seite zu <br />
									<input type="checkbox" class="agbModalCheck checkCookies" name="Cookies" value="1"> Ich stimme dem Setzen von Cookies in meinem Browser für die Funktionalität dieses OnlineShops zu
                  <button type="button" disabled="true" class="btn btn-default agbModalButton disabled" data-dismiss="modal">Ok</button>
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
	<script src="js/login.js"></script>
</html>
