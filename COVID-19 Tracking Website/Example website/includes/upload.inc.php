<!-- This file handles product uploads -->
<?php

if (!isset($_POST["submit"])) {
    header("location: ../store.php");
    exit();
}

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

// Grab all the needed product data.
$name =         $_POST['itemName'];
$desc =         $_POST['itemDescription'];
$price =        $_POST['itemPrice'];
$quantity =     $_POST['itemQuantity'];
$file =         $_FILES['itemImage'];
$fileName =     $file["name"];
$fileTmpName =  $file["tmp_name"];
$fileError =    $file["error"];
$fileSize =     $file["size"];

$fileExtension = explode(".", $fileName);
$realExtension = (strtolower(end($fileExtension)));
$allowedExtensions = array("jpeg", "jpg", "png");

// Sends error if the product image is bigger than 20 MB. 
if ($fileSize > 20000000) {
    header('location: ../store.php?error=invalidFileSize');
    exit();
}
//Price shouldn't be 0 or less
if ($price <= 0) {
    header('location: ../store.php?error=invalidPrice');
    exit();
}
// Quantity should not be negative
if ($quantity < 0) {
    header('location: ../store.php?error=invalidQuantity');
    exit();
}
// General file error, shouldn't happen
if ($fileError !== 0) {
    header('location: ../store.php?error=invalidFile');
    exit();
}
// Only Jpg, Jpeg and png images are allowed
if (!in_array($realExtension, $allowedExtensions)) {
    header('location: ../store.php?error=invalidFileExtension');
    exit();
}

// Create the name the product image on the server.
$imageUploadName = uniqid("productID_", false) . "." . $realExtension;
// The filepath to the image on the server, to be used in the database.Ф
$imageUploadFilepath = "../img/store/" . $imageUploadName;

// Run the SQL query 
$sqlSelect = "INSERT INTO products (productsQuantity, productsPrice, productsName, productsDescription, productsImageFilepath) VALUES (?, ?, ?, ?, ?);";
$statement = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
    header("location: ../store.php?error=statmentfailed");
    exit();
}
// Create a new product entity
mysqli_stmt_bind_param($statement, "idsss", $quantity, $price, $name, $desc, $imageUploadFilepath);
mysqli_stmt_execute($statement);
// Upload the file to the server with its new name
move_uploaded_file($fileTmpName, $imageUploadFilepath);
header("location: ../store.php");
