<?php

include_once "dbh.inc.php";
include_once "functions.inc.php";

// This happens if the Delete button was clicked
if (isset($_POST['remove'])) {
    $userId = $_POST['usersId'];

    $sql = "DELETE FROM users WHERE usersId=" . $userId;
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        header("location: ../account.php?delete=failed");
    }

    mysqli_stmt_execute($statement);
    header("location: ../account.php?delete=complete");
    exit();
}


// This happens if the Update button was clicked
if (isset($_POST['update'])) {

    $userId = $_POST['usersId'];
    $userName = $_POST['username'];
    $userEmail = $_POST['email'];

    // String to display upon succesful update
    $location = "location: ../account.php?id=" . $userId . "&";

    if ($userName != null && !usernameInvalid($userName) === true && !userExists($conn, $userName, $userName) === true) {

        $sql = "UPDATE users SET usersUsername=? WHERE usersId= ?";
        $statement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("location: ../account.php?error=statementfailed");
            exit();
        }

        mysqli_stmt_bind_param($statement, "si", $userName, $userId);
        mysqli_stmt_execute($statement);
        $location = $location . "username=done&";
    } else {
        $location = $location . "username=" . $userName . "&";
    }

    if ($userEmail != null && !emailInvalid($userEmail) === true && !userExists($conn, $userEmail, $userEmail) === true) {

        $sql = "UPDATE users SET usersEmail=? WHERE usersId= ?";
        $statement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("location: ../account.php?error=statementfailed");
            exit();
        }

        mysqli_stmt_bind_param($statement, "si", $userEmail, $userId);
        mysqli_stmt_execute($statement);

        $location = $location . "email=done";
    } else {
        $location = $location . "email=" . $userEmail;
    }

    header($location);
    exit();
}

// Returns user to account page if this document has been accessed improperly
header("location: ../account.php");
