<?php

// Make sure the user accessed the page properly
if (!isset($_POST["submit"])) {
    // header("location: ../signup.php");
    echo var_dump($_POST);
    exit();
}


require_once 'connect.php';
require_once 'regFunctions.inc.php';



$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confPassword = $_POST["confirm_password"];
$academicID = $_POST["academicID"];
$academicTitle = $_POST["academicTitle"];
$academicInstitute = $_POST["institution"];

// Makes sure the user has entered data to all fields. 
if (isEmpty($username, $email, $password, $confPassword, $academicID, $academicTitle, $academicInstitute) !== false) {
    header("location: ../signup.php?error=emptyinput");
    exit();
}

// Make sure the username is valid
if (usernameInvalid($username) !== false) {
    header("location: ../signup.php?error=invalidusername");
    exit();
}

if (userExists($connect, $email) !== false) {
    header("location: ../signup.php?error=usertaken");
    exit();
}

// Make sure the email is valid
if (emailInvalid($email) !== false) {
    header("location: ../signup.php?error=invalidemail");
    exit();
}

// Make sure the password is of acceptable length match
if (invalidPassLength($password) !== false) {
    header("location: ../signup.php?error=shortpassword");
    exit();
}

// Make sure the passwords match
if (passwordMatch($confPassword, $password) !== false) {
    header("location: ../signup.php?error=passwordmismatch");
    exit();
}

createUser($connect, $username, $password, $email, $academicID, $academicTitle, $academicInstitute);
exit();