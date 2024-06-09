<?php

// Make sure the user accessed the page properly
if (!isset($_POST["submit"])) {
    header("location: ../register.php");
    exit();
}


require_once 'dbh.inc.php';
require_once 'functions.inc.php';

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confPassword = $_POST["conf_password"];

// Makes sure the user has entered data to all fields. 
// Needless as the form requires all of them but here just in case
if (isEmpty($username, $email, $password, $confPassword) !== false) {
    header("location: ../register.php?error=emptyinput");
    exit();
}

// Make sure the username is valid
if (usernameInvalid($username) !== false) {
    header("location: ../register.php?error=invalidusername");
    exit();
}

if (userExists($conn, $username, $email) !== false) {
    header("location: ../register.php?error=usertaken");
    exit();
}

// Make sure the email is valid
if (emailInvalid($email) !== false) {
    header("location: ../register.php?error=invalidemail");
    exit();
}

// Make sure the password is of acceptable length match
if (invalidPassLength($password) !== false) {
    header("location: ../register.php?error=shortpassword");
    exit();
}

// Make sure the passwords match
if (passwordMatch($confPassword, $password) !== false) {
    header("location: ../register.php?error=passwordmismatch");
    exit();
}

createUser($conn, $username, $password, $email);
