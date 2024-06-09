<?php
include_once "connect.php";
include_once "functions.php";
include_once '../head.php';

// Check if this has been accessed via form, and if so, if the user is an admin.
if (isset($_POST['submit']) && isset($_SESSION['userType']) && $_SESSION['userType'] > 0) {

    $data = $_POST['input'];
    $location = "Location: ../admin.php";
    $dataTypes = "";
    $action = "DELETE";


    // If the deletion button is prompted, send a delete request.
    if ($_POST['submit'] == "Delete") {
        $query = "INSERT INTO pending (PendingAdminID, PendingTarget,PendingDataAction, PendingDataTypes, PendingDataValues, PendingDataDate) VALUES (?, ?,?, ?, ?, CURDATE())";
        $statement = mysqli_stmt_init($connect);
        $values = [' ', $data[0]];
        if (!mysqli_stmt_prepare($statement, $query)) {

            // echo mysqli_error($connect);
            header($location . "?error=badstatement");
            exit();
        }
        mysqli_stmt_bind_param($statement, "issss", $_SESSION['userID'], $_POST['table'], $action, ...$values);
        mysqli_stmt_execute($statement);

        
        logger("Requested Deletion in Table: [" . $_POST['table'] . "] of row ID: " . $data[0], $connect);
        
        header($location . "?deletion=done");
        exit();
    }

    // Alternatively, send an update request.
    $query = "INSERT INTO pending (PendingAdminID, PendingTarget,PendingDataAction, PendingDataTypes, PendingDataValues, PendingDataDate) VALUES (?, ?,?, ?, ?, CURDATE())";
    $pendingDataValues = '';

    switch ($_POST['table']) {
        case "postcodes":
            $dataTypes = "isddiisiii";
            break;

        case "academics":
            $dataTypes = "isssssis";
            break;

        case "coviddata":
            $dataTypes = "iissiiii";
            break;

        case "applicants":
            $dataTypes = "issi";
            break;

        default:
            $location = $location . "?error=badtable";
    }

    for ($i = 0; $i < count($data); $i++)
        $pendingDataValues = $pendingDataValues . $data[$i] . ";";
    $statement = mysqli_stmt_init($connect);

    if (!mysqli_stmt_prepare($statement, $query)) {

        echo mysqli_error($connect);
        // header($location . "?error=badstatement");
        exit();
    }

    $action = "UPDATE";

    mysqli_stmt_bind_param($statement, "issss", $_SESSION['userID'], $_POST['table'], $action, $dataTypes, $pendingDataValues);
    mysqli_stmt_execute($statement);



    logger("Requested Update in Table: [" . $_POST['table'] . "] of row ID: " . $data[0], $connect);
    header($location . "?request=done");
    exit();
}


header("Location: ../index.php");
exit();
