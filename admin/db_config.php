<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brotportal";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database
$sql = "CREATE DATABASE " . $dbname;
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "DROP TABLE products;";
$conn->query($sql);
$sql = "DROP TABLE users;";
$conn->query($sql);
$sql = "DROP TABLE orders;";
$conn->query($sql);


$sql = "CREATE TABLE products (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
productID VARCHAR(10) NOT NULL,
name VARCHAR(40) NOT NULL,
description VARCHAR(140)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table products created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
customerID VARCHAR(10) NOT NULL,
password VARCHAR(15),
mailAdress VARCHAR (40)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE TABLE orders(
productID INT(6) UNSIGNED,
customerID INT(6) UNSIGNED,
orderDate DATE,
number INT(6),
PRIMARY KEY(productID, customerID, orderDate)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table orders created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?> 