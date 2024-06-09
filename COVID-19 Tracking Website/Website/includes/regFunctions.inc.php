<?php

/*Returns TRUE if any field is empty*/
function isEmpty($username, $email, $password, $confPassword, $academicID, $academicTitle, $academicInstitute)
{

    if (empty($username) || empty($email) || empty($password) || empty($confPassword) || empty($academicID) || empty($academicTitle) || empty($academicInstitute))
        return true;

    return false;
}

function isEmptyLogin($username, $password)
{

    if (empty($username) || empty($password))
        return true;

    return false;
}

// Returns TRUE if the username contains any characters except letters, numbers, _ or -
function usernameInvalid($username)
{

    if (!preg_match("/^[a-zA-Z0-9_-]*$/", $username))
        return true;

    return false;
}

// Returns TRUE if the email is invalid
function emailInvalid($email)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;


    return false;
}

// Returns TRUE if the passwords DON'T match
function passwordMatch($password, $confPassword)
{

    if ($password !== $confPassword)
        return true;

    return false;
}

// Returns TRUE if the password is under length of 6
function invalidPassLength($password)
{
    $result = false;

    if (strlen($password) < 6)
        $result = true;


    return $result;
}

// Check if a user already exists in the database, if so then return that user row as an associative array.
//  Else return false
function userExists($conn, $email)
{
    $statement = mysqli_stmt_init($conn);

    $sqlSelect = "SELECT * FROM applicants WHERE ApplicantEmail=?";
    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        header("location: ../signup.php?error=statmentfailed");
        // echo mysqli_stmt_error($statement);
        exit();
    }

    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);
    $resultData = mysqli_stmt_get_result($statement);
    if (!mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($conn);
        return true;
    }


    $sqlSelect = "SELECT * FROM users WHERE UserEmail=?;";

    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        header("location: ../signup.php?error=statmentfailed");
        // echo mysqli_stmt_error($statement);
        exit();
    }

    mysqli_stmt_bind_param($statement, "s", $email);
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
function createUser($conn, $username, $password, $email, $academicID, $academicTitle, $academicInstitute)
{
    $statement = mysqli_stmt_init($conn);

    $sqlSelect = "INSERT INTO users (UserName, UserEmail, UserPassword, UserCreatedOn, UserPrivilege) VALUES (?, ?, ?, CURRENT_DATE, 0);";
    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        // header("location: ../signup.php?error=statmentfailed");
        echo mysqli_stmt_error($conn);
        exit();
    }

    $passHash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($statement, "sss", $username, $email, $passHash);
    mysqli_stmt_execute($statement);



    $userid = mysqli_insert_id($conn);
    $sqlSelect = "INSERT INTO academics(AcademicID, AcademicTitle, AcademicInstitution, AcademicInstitutional_ID, AcademicIsApproved) VALUES (?,?,?,?,0);";
    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        header("location: ../signup.php?error=statmentfailed");
        // echo mysqli_stmt_error($conn);
        exit();
    }
    mysqli_stmt_bind_param($statement, "isss", $userid, $academicTitle, $academicInstitute, $academicID);
    mysqli_stmt_execute($statement);


    $sqlSelect = "DELETE FROM applicants WHERE ApplicantEmail = ?";
    if (!mysqli_stmt_prepare($statement, $sqlSelect)) {
        // header("location: ../signup.php?error=statmentfailed");
        echo mysqli_stmt_error($conn);
        exit();
    }
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);


    mysqli_stmt_close($statement);
    header("location: ../signup.php?error=none");
    exit();
}

function loginUser($conn, $username, $password)
{
    // Check if the user exists, provide username for both the name and the mail 
    // to allow for email logins.
    $user_row = userExists($conn, $username, $username);

    if ($user_row === false) {
        header("location: ../login.php?error=usernotfound");
        // echo mysqli_stmt_error($conn);
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
    $_SESSION["userID"] = $user_row["usersId"];
    $_SESSION["userName"] = $user_row["usersUsername"];
    $_SESSION["userEmail"] = $user_row["usersEmail"];
    $_SESSION["userType"] = $user_row["usersAdminStatus"];
    header("location: ../index.php");
}
