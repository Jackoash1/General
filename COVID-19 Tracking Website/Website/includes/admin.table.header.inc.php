<?php
include_once '../head.php';
// This script is responsible for setting administration table order column

// This file only sets a session variable, so no safety checking should be required.
$head = $_POST['header'];

// If the colum we are ordering by has been clicked again, swap ordering.
if ($_SESSION['adminOrderBy'] == $head) {

    if ($_SESSION['adminOrderType'] == "ASC")
        $_SESSION['adminOrderType'] = "DESC";
    else
        $_SESSION['adminOrderType'] = "ASC";
}
$_SESSION['adminOrderBy'] = $head;


header('Location: ../admin.php');
exit();
