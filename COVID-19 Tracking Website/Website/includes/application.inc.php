<?php
include_once "functions.php";
include_once "../head.php";
include_once "connect.php";


if (isset($_POST['submit']) && $_SESSION['userType']) {

    $applicant = $_POST['input'];

    if ($_POST['submit'] == 'Approve') {

        $isApproved = 1;
        $location = "location: ../admin.php?id=" . $applicant[0] . "&approved";
        $sql = "UPDATE applicants SET ApplicantApproved=? WHERE ApplicantID= ?";
        $statement = mysqli_stmt_init($connect);

        // If error occurs exit and send error msg.
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("location: ../admin.php?error=approval_Fail");
            exit();
        }

        mysqli_stmt_bind_param($statement, "ii", $isApproved, $applicant[0]);
        mysqli_stmt_execute($statement);
        header($location);
        exit();
    }

    if ($_POST['submit' == 'Delete']) {
        $sql = "DELETE FROM applicants WHERE ApplicantID=" . $applicant[0];
        $statement = mysqli_stmt_init($connect);

        //Send rejection error message if error occurs.
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("location: ../admin.php?reject=failed");
        }

        mysqli_stmt_execute($statement);
        header("location: ../admin.php?delete=complete");
        exit();
    }
}

header("location: ../admin.php?error=generic");
exit();
