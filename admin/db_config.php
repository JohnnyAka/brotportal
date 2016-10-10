<?php
include('db_crud.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brotportal";

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
$sql = "DROP TABLE categoryRelations;";
$conn->query($sql);
$sql = "DROP TABLE settings;";
$conn->query($sql);


$sql = "CREATE TABLE products (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
productID VARCHAR(10) NOT NULL,
name VARCHAR(40) NOT NULL,
productCategory INT(6),
visibleForUser TINYINT(1),
description VARCHAR(140),
imagePath VARCHAR(140),
ingredients VARCHAR(200),
allergens VARCHAR(200),
weight VARCHAR(10),
preBakeExp INT(3) UNSIGNED,
featureExp VARCHAR(200)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table products created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
customerID VARCHAR(10) NOT NULL,
name VARCHAR (40),
password VARCHAR(20),
customerCategory INT(6),
mailAdressTo VARCHAR (40),
mailAdressReceive VARCHAR (40),
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

$sql = "CREATE TABLE orders(
idProduct INT(6) UNSIGNED,
idCustomer INT(6) UNSIGNED,
orderDate DATE,
number INT(6),
hook INT(1),
important TINYINT(1),
noteBaking VARCHAR(200),
noteDelivery VARCHAR(200),
PRIMARY KEY(idProduct, idCustomer, orderDate, hook)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table orders created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$sql = "CREATE TABLE productCategories(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR (60)
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

$sql = "CREATE TABLE settings (
adminName VARCHAR(40),
adminPassword VARCHAR(40),
deleteOrdersInDays INT(6),
imagesPath VARCHAR(400),
autoExportOrdersTime TIME,
exportOrdersTo VARCHAR(400),
saveDatabaseTo VARCHAR(400)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table settings created successfully<br>";
		$db = new db_connection();
		$result = $db->createData("settings",array('adminName','adminPassword','deleteOrdersInDays','imagesPath','exportOrdersTo','saveDatabaseTo'), array('admin','password','30','/','/','/'));
		echo 'Settings: '.$result;
} else {
    echo "Error creating database: " . $conn->error."<br>";
}

$conn->close();
?> 