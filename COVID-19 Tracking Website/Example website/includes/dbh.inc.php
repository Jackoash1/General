<?php

$serverName = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dBName = "internet_technologies_db";

// Establish connection with MySQL
$conn = mysqli_connect($serverName, $dbUserName, $dbPassword, $dBName);

// Send error message if connection fails
if (!$conn) {
    die("Connection with MySQL failed. Error: " . mysqli_connect_error());
}
