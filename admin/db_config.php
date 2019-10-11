<?php
include('db_config_crud.php');


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brotportal";
//Datenbank Zugangsdaten
$adminUser = "bestellannahme";
$adminUserPw = "password";

$customer = "kunde";
$customerPw = "password";

//admin Zugangsdaten
$adminName = "bestellannahme";
$adminPassword = "halleluja";

//Erinnerung: Wenn neuer table hinzugefügt wird müssen drop und permissions gesetzt werden

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error."<br>");
} 

// Create database
$sql = "CREATE DATABASE " . $dbname;
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error)."<br>";
} 

$db = new db_connection();

$sql = "DROP TABLE products;";
$conn->query($sql);
$sql = "DROP TABLE users;";
$conn->query($sql);
$sql = "DROP TABLE orders;";
$conn->query($sql);
$sql = "DROP TABLE productCategories;";
$conn->query($sql);
$sql = "DROP TABLE userCategories;";
$conn->query($sql);
$sql = "DROP TABLE prizeCategories;";
$conn->query($sql);
$sql = "DROP TABLE categoryRelations;";
$conn->query($sql);
$sql = "DROP TABLE settings;";
$conn->query($sql);
$sql = "DROP TABLE calendars;";
$conn->query($sql);
$sql = "DROP TABLE calendarsDaysRelations;";
$conn->query($sql);

$sql = "CREATE TABLE products (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
productID VARCHAR(10) NOT NULL,
name VARCHAR(80) NOT NULL,
productCategory INT(6),
orderPriority INT(2) DEFAULT '50',
visibleForUser TINYINT(1),
description VARCHAR(500),
imagePath VARCHAR(200),
ingredients VARCHAR(500),
allergens VARCHAR(200),
weight VARCHAR(10),
preBakeExp INT(3) UNSIGNED,
preBakeMax INT(3) UNSIGNED,
featureExp VARCHAR(200),
price1 DECIMAL(8,2),
price2 DECIMAL(8,2),
price3 DECIMAL(8,2),
price4 DECIMAL(8,2),
price5 DECIMAL(8,2),
idCalendar INT(6)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table products created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
customerID VARCHAR(10) NOT NULL,
preOrderCustomerId VARCHAR(10) NOT NULL,
name VARCHAR (80),
password VARCHAR(80),
customerCategory INT(6),
priceCategory INT(1),
mailAdressTo VARCHAR (80),
mailAdressReceive VARCHAR (200),
telephone1 VARCHAR (25),
telephone2 VARCHAR (25),
fax VARCHAR (25),
storeAdress VARCHAR (120),
whereToPutOrder VARCHAR(200)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

//hook=1 is Lieferung 1, hook=5 is Nachlieferung
$sql = "CREATE TABLE orders(
idProduct INT(6) UNSIGNED,
idCustomer INT(6) UNSIGNED,
orderDate DATE,
number INT(6),
hook INT(1),
important TINYINT(1),
noteBaking VARCHAR(200),
noteDelivery VARCHAR(200),
locked TINYINT(1) DEFAULT '0',
PRIMARY KEY(idProduct, idCustomer, orderDate, hook)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table orders created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE productCategories(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR (60),
orderPriority INT(2) DEFAULT '50'
)";
if ($conn->query($sql) === TRUE) {
    echo "Table productCategories created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE userCategories(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR (60)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table userCategories created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE prizeCategories(
id INT(2) UNSIGNED PRIMARY KEY,
name VARCHAR (60),
infoText VARCHAR (100)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table prizeCategories created successfully<br>";
		$result = $db->createData("prizeCategories",array('id','name','infoText'), array('1','Preis 1',' € (Empfohlener Verkaufspreis)'));
		$result = $db->createData("prizeCategories",array('id','name','infoText'), array('2','Preis 2',' € (Netto-Einkaufspreis)'));
		$result = $db->createData("prizeCategories",array('id','name','infoText'), array('3','Preis 3',' € (Sonderpreis)'));
		$result = $db->createData("prizeCategories",array('id','name','infoText'), array('4','Preis 4',' € (Sonderpreis)'));
		$result = $db->createData("prizeCategories",array('id','name','infoText'), array('5','Preis 5',' € (Sonderpreis)'));
		echo 'Preiskategorien angelegt</br>';
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE categoryRelations(
idUserCat INT(6) UNSIGNED,
idProductCat INT(6) UNSIGNED,
primary key (idUserCat, idProductCat)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table categoryRelations created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE calendars(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR (60)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table calendars created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE calendarsDaysRelations(
idCalendar INT(6) UNSIGNED,
date DATE,
primary key (idCalendar, date)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table calendarsDaysRelations created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE settings (
adminName VARCHAR(40),
adminPassword VARCHAR(40),
deleteOrdersInDays INT(6),
imagesPath VARCHAR(400),
endOfOrderTime TIME,
exportOrdersTo VARCHAR(400),
saveDatabaseTo VARCHAR(400)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table settings created successfully<br>";
		$result = $db->createData("settings",array('adminName','adminPassword','deleteOrdersInDays','imagesPath','exportOrdersTo','saveDatabaseTo'), array($adminName,$adminPassword,'30','/','/','/'));
		echo 'Settings: '.$result.'<br>';
} else {
    echo "Error creating database: " . $conn->error."<br>";
}
//create users and grant permissions
echo "<br />Setting permissons:<br />";
$tablesAdminUser = array('products','users','orders','productCategories','userCategories','prizeCategories','categoryRelations','calendars','calendarsDaysRelations');
foreach($tablesAdminUser as $table){
	$sql = "grant all on brotportal.".$table." to ".$adminUser."@localhost identified by '".$adminUserPw."'";
	if ($conn->query($sql) === TRUE) {
			echo $adminUser." permissions for ".$table." set successfully<br>";
	} else {
			echo "Error granting permissions: " . $conn->error."<br>";
	}
}
$sql = "grant select (deleteOrdersInDays,imagesPath,endOfOrderTime,exportOrdersTo,saveDatabaseTo,adminName,adminPassword),
							update (deleteOrdersInDays,imagesPath,endOfOrderTime,exportOrdersTo,saveDatabaseTo)
	on brotportal.settings to ".$adminUser."@localhost identified by '".$adminUserPw."'";
if ($conn->query($sql) === TRUE) {
	echo $adminUser." permissions for settings set successfully<br>";
} else {
	echo "Error granting permissions: " . $conn->error."<br>";
}

$tablesSelectCustomer = array('products','users','orders','productCategories','userCategories','prizeCategories','categoryRelations','calendars','calendarsDaysRelations');
foreach($tablesSelectCustomer as $table){
	$sql = "grant select on brotportal.".$table." to ".$customer."@localhost identified by '".$customerPw."'";
	if ($conn->query($sql) === TRUE) {
			echo $customer." select permissions for ".$table." set successfully<br>";
	} else {
			echo "Error granting permissions: " . $conn->error."<br>";
	}
}

$sql = "grant select (imagesPath,endOfOrderTime) on brotportal.settings to ".$customer."@localhost identified by '".$customerPw."'";
if ($conn->query($sql) === TRUE) {
	echo $customer." permissions for settings set successfully<br>";
} else {
	echo "Error granting permissions: " . $conn->error."<br>";
}

$sql = "grant all on brotportal.orders to ".$customer."@localhost identified by '".$customerPw."'";
if ($conn->query($sql) === TRUE) {
	echo $customer." permissions for orders set successfully<br>";
} else {
	echo "Error granting permissions: " . $conn->error."<br>";
}

$sql = "grant update on brotportal.users to ".$customer."@localhost identified by '".$customerPw."'";
if ($conn->query($sql) === TRUE) {
	echo $customer." permissions for users set successfully<br>";
} else {
	echo "Error granting permissions: " . $conn->error."<br>";
}




$conn->close();
?> 













