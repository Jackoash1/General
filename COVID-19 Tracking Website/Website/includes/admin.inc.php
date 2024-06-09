<?php
include_once "../head.php";

// Kick the user out of this page if they aren't an admin
// It shouldn't be necessary.
if (!isset($_SESSION['userID']) && $_SESSION['userType'] > 0) {

    header("location: ../index.php");
    exit();
}

$_SESSION['adminTable'] = $_POST['tables'];
$_SESSION['adminPanelPage'] = $_POST['pageNum'];
$_SESSION['adminRowsPerPage'] = $_POST['rowNum'];

header("location: ../admin.php");
exit();
