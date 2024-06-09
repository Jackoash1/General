<?php

/*Returns TRUE if any field is empty*/
function isEmpty($username, $email, $password, $confPassword)
{

    if (empty($username) || empty($email) || empty($password) || empty($confPassword)) {
        return true;
    }
    return false;
}

function isEmptyLogin($username, $password)
{

    if (empty($username) || empty($password)) {
        return true;
    }
    return false;
}

// Returns TRUE if the username contains any characters except letters, numbers, _ or -
function usernameInvalid($username)
{

    if (!preg_match("/^[a-zA-Z0-9_-]*$/", $username)) {
        return true;
    }
    return false;
}

// Returns TRUE if the email is invalid
function emailInvalid($email)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}

// Returns TRUE if the passwords DON'T match
function passwordMatch($password, $confPassword)
{

    if ($password !== $confPassword) {
        return true;
    }
    return false;
}

// Returns TRUE if the password is under length of 6
function invalidPassLength($password)
{
    $result = false;

    if (strlen($password) < 6) {
        $result = true;
    }

    return $result;
}

// Check if a user already exists in the database, if so then return that user row as an associative array.
//  Else return false
function userExists($conn, $username, $email)
{
    $sqlSelect = "SELECT * FROM users WHERE usersUsername =? OR usersEmail=?;";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        header("location: ../register.php?error=statmentfailed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "ss", $username, $email);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    // Return an associative array with the user's data if it exists, otherwise return false
    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($statement);
        return $row;
    } else {
        mysqli_stmt_close($statement);
        return false;
    }
}
// Update database with new user entry. 
function createUser($conn, $username, $password, $email)
{
    $sqlSelect = "INSERT INTO users (usersUsername, usersEmail, usersPass) VALUES(?,?,?);";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        header("location: ../register.php?error=statmentfailed");
        exit();
    }

    $passHash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($statement, "sss", $username, $email, $passHash);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
    header("location: ../register.php?error=none");
}

function loginUser($conn, $username, $password)
{
    // Check if the user exists, provide username for both the name and the mail 
    // to allow for email logins.
    $user_row = userExists($conn, $username, $username);

    if ($user_row === false) {
        header("location: ../login.php?error=usernotfound");
        exit();
    }

    // Grab the password from the mysql database
    $hashedPassword = $user_row["usersPass"];
    $passowrdMatch = password_verify($password, $hashedPassword);

    // If the password doesn't match, send error
    if ($passowrdMatch === false) {
        header("location: ../login.php?error=invalidpassword");
        exit();
    }

    // Return user to index after starting a session
    session_start();
    $_SESSION["userId"] = $user_row["usersId"];
    $_SESSION["userName"] = $user_row["usersUsername"];
    $_SESSION["userEmail"] = $user_row["usersEmail"];
    $_SESSION["isAdmin"] = $user_row["usersAdminStatus"];
    header("location: ../index.php");
}
