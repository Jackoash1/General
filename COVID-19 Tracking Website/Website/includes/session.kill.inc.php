<?php
include_once "functions.php";
include_once "../head.php";

session_unset();
session_destroy();

header("Location: ../index.php");
exit();
