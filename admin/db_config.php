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
$sql = "DROP TABLE productTags;";
$conn->query($sql);
$sql = "DROP TABLE productTagRelations;";
$conn->query($sql);
$sql = "DROP TABLE productLabels;";
$conn->query($sql);
$sql = "DROP TABLE productLabelRelations;";
$conn->query($sql);

$sql = "CREATE TABLE products (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
productID VARCHAR(10) NOT NULL,
name VARCHAR(80) NOT NULL,
productCategory INT(6),
orderPriority INT(2) DEFAULT '50',
visibleForUser TINYINT(1) DEFAULT '1',
description VARCHAR(1000),
imagePath VARCHAR(200),
imagePathSmall VARCHAR(200),
imagePathBig VARCHAR(200),
ingredients VARCHAR(1000),
allergens VARCHAR(300),
weight VARCHAR(10),
preBakeExp INT(3) UNSIGNED DEFAULT '0',
preBakeMax INT(3) UNSIGNED DEFAULT '1',
featureExp VARCHAR(200),
price1 DECIMAL(8,2),
price2 DECIMAL(8,2),
price3 DECIMAL(8,2),
price4 DECIMAL(8,2),
price5 DECIMAL(8,2),
salesTax DECIMAL(2,2),
vpe INT(6) UNSIGNED,
vpePricePerUnit DECIMAL(8,2),
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
password VARCHAR(255),
customerCategory INT(6),
priceCategory INT(1),
discountRelative DECIMAL(3,3) UNSIGNED DEFAULT '0',
warningThreshold INT(6) UNSIGNED DEFAULT '25',
autoSendOrders  TINYINT(1) DEFAULT '1',
mailAdressTo VARCHAR (100),
mailAdressReceive VARCHAR (300),
telephone1 VARCHAR (25),
telephone2 VARCHAR (25),
fax VARCHAR (25),
storeAdress VARCHAR (120),
whereToPutOrder VARCHAR(200),
agreedAGBs INT(1) DEFAULT '0'
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
orderPriority INT(2) DEFAULT '50',
upperCategoryID INT(6)
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

$sql = "CREATE TABLE productTags (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(80) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table productTags created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE productTagRelations(
idProduct INT(6) UNSIGNED,
idProductTag INT(6) UNSIGNED,
searchWeight DECIMAL(6,5) DEFAULT '1',
primary key (idProduct, idProductTag)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table productTagRelations created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE productLabels (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
imagePath VARCHAR(200),
imagePathSmall VARCHAR(200)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table productLabels created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE productLabelRelations(
idProduct INT(6) UNSIGNED,
idProductLabel INT(6) UNSIGNED,
showOnProductList INT(1),
showOnProductPass INT(1),
primary key (idProduct, idProductLabel)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table productLabelRelations created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE settings (
adminName VARCHAR(40),
adminPassword VARCHAR(255),
deleteOrdersInDays INT(6),
imagesPath VARCHAR(400),
endOfOrderTime TIME,
exportOrdersTo VARCHAR(400),
saveDatabaseTo VARCHAR(400)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table settings created successfully<br>";
		$passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);
		$result = $db->createData("settings",array('adminName','adminPassword','deleteOrdersInDays','imagesPath','exportOrdersTo','saveDatabaseTo'), array($adminName,$passwordHash,'30','/','/','/'));
		echo 'Settings: '.$result.'<br>';
} else {
    echo "Error creating database: " . $conn->error."<br>";
}
//create users and grant permissions
echo "<br />Setting permissons:<br />";
$tablesAdminUser = array('products','users','orders','productCategories','userCategories','prizeCategories','categoryRelations','calendars','calendarsDaysRelations','productTags','productTagRelations','productLabels','productLabelRelations');
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

$tablesSelectCustomer = array('products','users','orders','productCategories','userCategories','prizeCategories','categoryRelations','calendars','calendarsDaysRelations','productTags','productTagRelations','productLabels','productLabelRelations');
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













