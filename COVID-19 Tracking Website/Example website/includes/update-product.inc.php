<?php

if (!isset($_POST["submit"])) {
    // It's difficult to track previous entry point if entry method is invalid so the user gets sent back to the store page.
    header("location: ../store.php");
    exit();
}

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

// The ID of the item we wish to update.
$prodId =       $_POST["itemId"];

$invalidInfoHeader = "location: ../product.php?id=" . $prodId . "&error=";

// The new set of ID's we wish to replace.
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

// Create the name the product image on the server.
$imageUploadName = uniqid("productID_", false) . "." . $realExtension;
// The filepath to the image on the server, to be used in the database.Ф
$imageUploadFilepath = "../img/store/" . $imageUploadName;

// Update the name if not null
if ($name != null) {
    $sql = "UPDATE products SET productsName = ? WHERE productsId=?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        header($invalidInfoHeader . "statmentfailed");
        exit();
    }
    mysqli_stmt_bind_param($statement, "si", $name, $prodId);
    mysqli_stmt_execute($statement);
}

// Update the description if a new one has been provided
if ($desc != null) {
    $sql = "UPDATE products SET productsDescription = ? WHERE productsId=?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        header($invalidInfoHeader . "statmentfailed");
        exit();
    }
    mysqli_stmt_bind_param($statement, "si", $desc, $prodId);
    mysqli_stmt_execute($statement);
}
// Update the price if a new one has been provided that is over 0
if ($price != null && $price > 0) {
    $sql = "UPDATE products SET productsPrice = ? WHERE productsId=?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        header($invalidInfoHeader . "statmentfailed");
        exit();
    }
    mysqli_stmt_bind_param($statement, "si", $price, $prodId);
    mysqli_stmt_execute($statement);
}
// Updates the qunatity with any that is 0 or above 
if ($quantity != null && $quantity >= 0) {
    $sql = "UPDATE products SET productsQuantity = ? WHERE productsId=?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        header($invalidInfoHeader . "statmentfailed");
        exit();
    }
    mysqli_stmt_bind_param($statement, "si", $quantity, $prodId);
    mysqli_stmt_execute($statement);
}

// If the file: is less than 20mb, not null, throws no errors and of the allowed extension, then remove its previous ID from the store folder and replace it.
if ($file != null && $fileSize < 20000000 && $fileError === 0 && in_array($realExtension, $allowedExtensions)) {

    // The SQL statement for the new image
    $updateSql = "UPDATE products SET productsImageFilepath = ? WHERE productsId=?";
    $uploadStatement = mysqli_stmt_init($conn);

    // The SQL statement to get the filepath of the old image
    $deleteSql = "SELECT productsImageFilepath FROM products WHERE productsId =" . $prodId;
    $deleteStatement = mysqli_stmt_init($conn);

    // Prepare the new image
    if (!mysqli_stmt_prepare($uploadStatement, $updateSql)) {
        header($invalidInfoHeader . "fileUploadStatementfailed");
        exit();
    }
    // Prepare the old image
    if (!mysqli_stmt_prepare($deleteStatement, $deleteSql)) {
        header($invalidInfoHeader . "fileDeleteStatementfailed");
        exit();
    }

    // Grab the old image's address
    mysqli_stmt_bind_param($uploadStatement, "si", $imageUploadFilepath, $prodId);
    mysqli_stmt_execute($deleteStatement);

    // This is probably needless.
    $deleteResult = mysqli_fetch_assoc(mysqli_stmt_get_result($deleteStatement));

    // Delete the old image and upload the new image and its location
    unlink($deleteResult['productsImageFilepath']);
    mysqli_stmt_execute($uploadStatement);
    move_uploaded_file($fileTmpName, $imageUploadFilepath);
}

header($invalidInfoHeader . "none");
