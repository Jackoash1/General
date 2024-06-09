<?php
include_once '../head.php';

// ========================= Handles Admin Page Form Navigation =================

// Kick the user out of this page if they aren't an admin
// It shouldn't be necessary.
if (!isset($_SESSION['userID']) && $_SESSION['userType'] > 0) {

    header("location: ../index.php");
    exit();
}

switch ($_POST['formTarget']) {

    case "table": //If table is selected, reset all values to their default state.
        $_SESSION['adminTable'] = $_POST['value'];
        $_SESSION['adminPanelPage'] = 0;
        $_SESSION['adminOrderType'] = 'ASC';
        $_SESSION['adminOrderBy'] = '';

        break;

    case "row":
        $_SESSION['adminRowsPerPage'] = $_POST['value'];
        break;

    case "page":

        if (isset($_POST['gt'])) {
            $_SESSION['adminPanelPage'] = $_POST['gt'];
            break;
        }
        if (isset($_POST['lt'])) {
            $_SESSION['adminPanelPage'] = $_POST['lt'];
            break;
        }

        $_SESSION['adminPanelPage'] = $_POST['value'];
        break;

    default:
        AdminPanel::setupAdminSession();
        break;
}

header("location: ../admin.php");
exit();
