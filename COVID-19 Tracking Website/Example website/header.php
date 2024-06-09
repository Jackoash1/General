<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSS and Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-sm pr-4 pl-4">

        <!-- Brand Logo -->
        <a href="index.php" class="navbar-brand">SPORT <img src="img/on.png" alt="logo"> ONLINE</a>

        <!-- Button that toggles the navigation closing/opening -->
        <button class="navbar-toggler" id="toggleBtn">
            <span class="navbar-toggler-icon navbar-dark"></span>
        </button>

        <!-- List of navigation options -->
        <div class="navbar-collapse collapse text-center" id="collapseNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="store.php" class="nav-link pr-3 pl-3">Products</a></li>
                <?php
                if (isset($_SESSION["userId"])) {
                    echo '<li class="nav-item"><a href="includes/logout.inc.php" class="nav-link pr-3 pl-3">Logout</a></li>';
                    echo '<li class="nav-item"><a href="account.php" class="nav-link pr-3 pl-3">Account</a></li>';
                } else {
                    echo '<li class="nav-item"><a href="login.php" class="nav-link pr-3 pl-3">Login</a></li>';
                    echo '<li class="nav-item"><a href="register.php" class="nav-link pr-3 pl-3">Register</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>