<?php
include_once(dirname(__FILE__) . '../includes/connect.php');
include_once('head.php');

//Send user to Index if they haven't logged in.
if (!isset($_SESSION["userID"])) {
    header('location: /index.php');
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="styles/account.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <!-- <script src="https://kit.fontawesome.com/cedd12acf6.js" crossorigin="anonymous"></script> -->
    <script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>
</head>

<body>
    <div class="wrapper">

        <div class="box-wrapper">
            <?php
            echo '<div class="std-box"> <b>Email: </b> ' . $_SESSION['userEmail'] . '</div>';
            echo '<div class="std-box"> <b>Name: </b> ' . $_SESSION['userName'] . '</div>';

            //Display various admin panels if the user is Admin or Super Admin
            if ($_SESSION['userType']) {
                echo '<div class="std-box"> <b>Mobile:</b> ' . $_SESSION['userPhone'] . '</div>';
            } else {
                echo '<div class="std-box"> <b>Institution: </b> ' . $_SESSION['userInstitution'] . '</div>';
                echo '<div class="std-box"> <b>Title: </b> ' . $_SESSION['userAcademicTitle'] . '</div>';
                echo '<div class="std-box"> <b>Academic ID: </b> ' . $_SESSION['userAcademicID'] . '</div>';
            }
            ?>


        </div>


        <form method="get" action="CombinedData_Clean.rar">
            <button class="button buttonDownload" type="submit">Download Database</button>
        </form>

    </div>
    <?php include_once('globals/navbar.php'); ?>

</body>

</html>