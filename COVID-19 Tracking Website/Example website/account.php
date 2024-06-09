<?php

include_once 'header.php';
include_once 'includes/dbh.inc.php';
if (!isset($_SESSION["userId"])) {
    header('location: index.php');
}

// Show user data. 
echo '
<div class="wrapper" id="userinfo">
    <div class="row">

        <div class="col-12 base-box shadow-std p-4 m-2">
            <h2>Username: <span style="color: var(--red)">' . $_SESSION['userName'] . '</span> </h2>
        </div>

        <div class="col-12 base-box shadow-std p-4 m-2">
            <h2>Email: <span style="color: var(--red)">' . $_SESSION['userEmail'] . '</span></h2 class="">
        </div>
    </div>
</div>
';
?>

<?php

if ($_SESSION['isAdmin']) {

    // Grab users by descending order
    $sql = "SELECT * FROM users ORDER BY usersId";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        echo "Statement failure";
    }

    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    echo '
    <div class="base-box shadow-std m-2 usersTbl">
    <table>
        <tr>
            <th class="text-center">User ID:</th>
            <th>Username:</th>
            <th>User Email:</th>
            <th class="text-center">Edit:</th>
            <th class="text-center">Delete:</th>
        </tr>
';

    // Dynamically fill in the table with all non-admin users. Admins shouldn't be able to delete admins.
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['usersAdminStatus'] != 1) {
            echo '
        <tr>
            <form action="includes/update-user.inc.php" autocomplete="off" method="POST">
                <input type="hidden" name="usersId" value="' . $row['usersId'] . '">
                <td class="text-center"><span style="color: var(--red);">' . $row['usersId'] . '</span></td>
                <td><input type="text" id="username' . $row['usersId'] . '" spellcheck="false" placeholder="username" name="username" value="' . $row['usersUsername'] . '" class="user-form"></td>
                <td><input type="text" id="email' . $row['usersId'] . '" spellcheck="false" placeholder="email" name="email" value="' . $row['usersEmail'] . '"class="user-form"></td>
                <td class="text-center"><Button type="submit" value="update" name="update" class="btn btn-dark shadow-std">Change</button></td>
                <td class="text-center"><button type="submit" value="remove" name="remove" class="btn btn-dark red shadow-std">Remove</button></td>
            </form>
        </tr>';
        }
    }

    echo '
    </table>
    </div>';


    // Makes fields red for invalid input
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $usernameState = $_GET['username'];
        $emailState = $_GET['email'];

        if ($usernameState != "done") {
            echo '
        <script>
            $(document).ready(function() {
                $("#username' . $id . '").addClass("user-form-invalid");
                $("#username' . $id . '").val("' . $usernameState . '");
            });
        </script>';
        } else {
            echo '
            <script>
                $(document).ready(function() {
                    $("#username' . $id . '").addClass("user-form-valid");
                });
            </script>';
        }
        if ($emailState != "done") {
            echo '
        <script>
            $(document).ready(function() {
                $("#email' . $id . '").addClass("user-form-invalid");
                $("#email' . $id . '").val("' . $emailState . '");
            });
        </script>';
        } else {
            echo '
            <script>
                $(document).ready(function() {
                    $("#email' . $id . '").addClass("user-form-valid");
                });
            </script>';
        }
    }
}
?>


<?php include_once 'footer.php' ?>