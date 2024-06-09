<?php
include_once "functions.php";
include_once "../head.php";
include_once "connect.php";

if (isset($_POST['submit']) && $_SESSION['userType'] == 2) {
    $location = "Location: ../admin.php";
    $data = $_POST['input'];


    if ($_POST['table'] == "pending") {
        $msg = "?deletion=done";
        $logMsg =  "Reject Request of Type: [" . $data[4] . "] made by: [" . $data[2] . "] on [" . $data[3] . "]";
        if ($_POST['submit'] == "Approve") {

            $table = $data[3];

            // Run if we are APPROVING updating a table
            if ($data[4] == "UPDATE") {

                $inputDataString = $data[6];

                $inputData = explode(";", $inputDataString);
                array_pop($inputData);

                $colNames = [];
                $tables = "";
                $conditional = "";

                // Check which table we are modifying.
                switch ($table) {
                    case "postcodes":
                        $tables = "postcodes";
                        $conditional = "WHERE PostCodeID= ?";
                        $colNames = ["PostCodeID", "PostCode", "PostCodeLatitude", "PostCodeLongitude", "PostCodePopulation", "PostCodeHouseholds", "PostCodeRegion", "PostCodeTotalPositive", "PostCodeTotalRecovered", "PostCodeTotalDeceased"];
                        break;
                    case "coviddata":
                        $tables = "coviddata, postcodes";
                        $conditional = "WHERE DataPostCodeID= PostCodeID AND DataID= ?";
                        $colNames = ["DataID", "PostCodeID", "PostCode", "DataDate", "DataPositive", "DataHospitalized", "DataIntensiveCare", "DataDeceased"];
                        break;
                    case "academics":
                        $tables = "users, academics";
                        $conditional = "WHERE UserID = AcademicID AND UserID= ?";
                        $colNames = ["UserID", "UserEmail", "UserName", "AcademicTitle", "AcademicInstitution", "AcademicInstitutional_ID", "AcademicIsApproved", "UserCreatedOn"];
                        break;
                    case "admins":
                        $table = "users, admins";
                        $conditional = "WHERE UserID= AdminID AND UserID= ?";
                        $colNames = ["UserID", "UserName", "UserEmail", "AdminPhone", "UserPrivilege", "UserCreatedOn"];
                    default:
                        header($location . "?error=pendingTableError");
                        exit();
                }

                // Create a querry for updating the table we have approved updating for.
                $query = "UPDATE " . $tables . " SET ";

                for ($i = 0; $i < count($colNames); $i++) {
                    $query = $query . $colNames[$i] . " = ?";
                    if ($i != count($colNames) - 1) $query = $query . ", ";
                    else $query = $query . " ";
                }

                // Attach the conditional statement to the generated querry.
                $query = $query . $conditional;

                // Initialize connection to DB.
                $statement = mysqli_stmt_init($connect);
                if (!mysqli_stmt_prepare($statement, $query)) {
                    // echo mysqli_error($connect);
                    header($location . "?error=statement");
                    exit();
                }


                // Tell the SQL querry to expect 1 more input and the value of it (The ID for the conditional checks).
                array_push($inputData, $inputData[0]);
                $data[5] = $data[5] . "i";

                // Execute the approval statement.
                mysqli_stmt_bind_param($statement, $data[5], ...$inputData);
                mysqli_stmt_execute($statement);

                // Denote we have done an approval.
                $msg = "?approval=done";
                $logMsg = "Approve Request of Type: [" . $data[4] . "] made by: [" . $data[2] . "] on [" . $data[3] . "]";
            }
        }
        // Run if we are APPROVING deleting a row from table.
        if ($data[4] == "DELETE") {

            $query = '';

            switch ($data[3]) {
                case "postcodes":
                    $query = "DELETE FROM postcodes  WHERE PostCodeID= ?";
                    break;
                case "coviddata":
                    $query = "DELETE FROM coviddata WHERE DataID= ?";
                    break;
                case "academics":
                    $query = "DELETE FROM users, academics WHERE UserID= AcademicID AND UserID= ?";
                    break;
                default:
                    header($location . "?error=pendingTableError");
                    exit();
            }

            $statement = mysqli_stmt_init($connect);
            if (!mysqli_stmt_prepare($statement, $query)) {
                // echo mysqli_error($connect);
                header($location . "?error=badDELETEStatement");
                exit();
            }

            mysqli_stmt_bind_param($statement, "i", $data[6]);
            mysqli_stmt_execute($statement);

            $msg = "?approveDelete=done";
            $logMsg = "Approve Request of Type: [" . $data[4] . "] made by: [" . $data[2] . "] on [" . $data[3] . "]";
        }


        // Regardless if we wish to reject the request or not, we need to delete it after.
        $query = "DELETE FROM pending WHERE PendingID= " . $data[0];
        $statement = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($statement, $query)) {
            // echo mysqli_error($connect);
            header($location . "?error=requestNotDeleted");
            exit();
        }
        mysqli_stmt_execute($statement);

        // If mgs is unchanged, a deletion is assumed.

        logger($logMsg, $connect);
        header($location . $msg);
        exit();
    }

    // This bit is almost identical to the one prior
    // Can very easily refractor it but I am rushed for time so I wont.

    if ($_POST['submit'] == "Update") {

        $formTable = $_POST['table'];
        $data = $_POST['input'];
        $query = "";
        $conditional = "";
        $dataTypes = "";
        $table = "";


        switch ($formTable) {
            case "postcodes":
                $dataTypes = "isddiisiii";
                $table = "postcodes";
                $conditional = "WHERE PostCodeID= ?";
                $colNames = ["PostCodeID", "PostCode", "PostCodeLatitude", "PostCodeLongitude", "PostCodePopulation", "PostCodeHouseholds", "PostCodeRegion", "PostCodeTotalPositive", "PostCodeTotalRecovered", "PostCodeTotalDeceased"];
                break;
            case "coviddata":
                $dataTypes = "iissiiii";
                $table = "coviddata, postcodes";
                $conditional = "WHERE DataPostCodeID= PostCodeID AND DataID= ?";
                $colNames = ["DataID", "PostCodeID", "PostCode", "DataDate", "DataPositive", "DataHospitalized", "DataIntensiveCare", "DataDeceased"];
                break;
            case "academics":
                $dataTypes = "isssssis";
                $table = "users, academics";
                $conditional = "WHERE UserID = AcademicID AND UserID= ?";
                $colNames = ["UserID", "UserEmail", "UserName", "AcademicTitle", "AcademicInstitution", "AcademicInstitutional_ID", "AcademicIsApproved", "UserCreatedOn"];
                break;
            case "admins":
                $dataTypes = "isssis";
                $table = "users, admins";
                $conditional = "WHERE UserID= AdminID AND UserID= ?";
                $colNames = ["UserID", "UserName", "UserEmail", "AdminPhone", "UserPrivilege", "UserCreatedOn"];
                break;
            default:
                header($location . "?error=pendingTableError");
                exit();
        }

        $query = "UPDATE " . $table . " SET ";
        $statement = mysqli_stmt_init($connect);
        $dataTypes = $dataTypes . "i";
        array_push($data, $data[0]);

        for ($i = 0; $i < count($colNames); $i++) {
            $query = $query . $colNames[$i] . " = ?";
            if ($i != count($colNames) - 1) $query = $query . ", ";
            else $query = $query . " ";
        }

        $query = $query . $conditional;

        if (!mysqli_stmt_prepare($statement, $query)) {
            // echo mysqli_error($connect);
            // echo '<br>';
            // echo $query;
            header($location . "?error=statement");
            exit();
        }


        mysqli_stmt_bind_param($statement, $dataTypes, ...$data);
        mysqli_stmt_execute($statement);

        $logMsg =  "Updated Table [" . $formTable . "] Row ID: " . $data[0];
        logger($logMsg, $connect);
        header($location . "?update=Successful");
        exit();
    }

    // If the admin deletes from any table but pending.
    if ($_POST['submit'] == "Delete") {


        $query = "DELETE FROM ";
        $table = "";
        $conditional = "";

        switch ($_POST["table"]) {
            case "postcodes":
                $table = "postcodes";
                $conditional = " WHERE PostCodeID= ?";
                break;
            case "admins":
            case "academics":
                $table = " users ";
                $conditional = " WHERE UserID= ?";
                break;
            case "coviddata":
                $conditional = " WHERE DataID= ?";
                break;

            default:

                header($location . "?error=pendingTableError");
                exit();
        }

        $query = $query . $table . $conditional;
        $statement = mysqli_stmt_init($connect);
        if (!mysqli_stmt_prepare($statement, $query)) {
            echo mysqli_error($connect);
            echo $query;
            // header($location . "?error=statement");
            exit();
        }
        mysqli_stmt_bind_param($statement, "i", $data[0]);
        mysqli_stmt_execute($statement);

        $logMsg =  "Deleted Table [" . $_POST['table'] . "] ID: " . $data[0];

        logger($logMsg, $connect);
        header($location . "?deletion=done");
        exit();
    }
}

header("Location: ../index.php");
exit();
