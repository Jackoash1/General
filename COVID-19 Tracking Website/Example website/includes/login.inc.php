<?php

// Make sure the user accessed the page properly
if (!isset($_POST["submit"])) {
    header("location: ../login.php");
    exit();
}

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

// Despite being named username, it can also be email
$username = $_POST["username"];
$password = $_POST["password"];

if (isEmptyLogin($username, $password) !== false) {
    header("location: ../login.php?error=emptyinput");
}

loginUser($conn, $username, $password);
