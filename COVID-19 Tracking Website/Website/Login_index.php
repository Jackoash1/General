<?php
session_start();

/*if(isset($_SESSION['loged'])&&($_SESSION['loged']==true))
{
    //WE ARE LOGGED SUCCESSFULY
	header('Location: test.php '); 
}
*/
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Login/Registration Page</title>
    <!-- <link rel="stylesheet" href="login_page.css"> -->
    <link rel="stylesheet" href="styles/login_page.css">
    <link rel="stylesheet" href="styles/navbar.css">

    <script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>
    <div id="container">
        <div class="loginbox">
            <h1>Login</h1>
            <form action="Login_page/loginDB.php" method="POST">
                <input class="inputText" type="text" name="login" placeholder="E-mail" />
                <input class="inputText" type="password" name="password" placeholder="Password" />
                <div class="form-footer">
                    <input class="btn" type="submit" value="Login" />
                    <button id="toRegisterFormBtn" class="btn" type="button">Don't have an account?</button>
                </div>
            </form>
            <?php if (isset($_SESSION['error'])) echo $_SESSION['error']; ?>
        </div>
        <div class="loginbox">
            <h1>Register</h1>
            <form method="post">
                <input class="inputText" type="text" name="ApplicantEmail" placeholder="E-mail" />
                <?php
                if (isset($_SESSION['error_ApplicantEmail'])) {
                    echo '<div class="error">' . $_SESSION['error_ApplicantEmail'] . '</div>';
                    unset($_SESSION['error_ApplicantEmail']);
                }
                ?>
                <!-- Reason of the application:  -->
                <textarea class="inputText reasonsArea" type="text" name="ApplicantApplication" placeholder="Reasons of the application"></textarea>
                <?php
                if (isset($_SESSION['error_empty_ApplicantApplication'])) {
                    echo '<div class="error">' . $_SESSION['error_empty_ApplicantApplication'] . '</div>';
                    unset($_SESSION['error_empty_ApplicantApplication']);
                }
                if (isset($_SESSION['error_ApplicantApplication'])) {
                    echo '<div class="error">' . $_SESSION['error_ApplicantApplication'] . '</div>';
                    unset($_SESSION['error_ApplicantApplication']);
                }
                ?>

                <div class="regulations-footer">
                    <input type="checkbox" name="checkbox">
                    <div>Accept terms and regulations</div>
                </div>
                <?php
                if (isset($_SESSION['error_checkbox'])) {
                    echo '<div class="error">' . $_SESSION['error_checkbox'] . '</div>';
                    unset($_SESSION['error_checkbox']);
                }
                ?>

                <div class="g-recaptcha" data-sitekey="6Lfp_LgeAAAAABfDPzXqtaIv8pasexv6qyAeGo4A"></div>
                <?php
                if (isset($_SESSION['error_robot'])) {
                    echo '<div class="error">' . $_SESSION['error_robot'] . '</div>';
                    unset($_SESSION['error_robot']);
                }
                ?>

                <div class="form-footer">
                    <button id="toLoginFormBtn" class="btn" type="button">Have an account?</button>
                    <input class="btn" type="submit" value="Apply">
                </div>

                <?php
                if (isset($_SESSION['applied'])) {
                    $_SESSION['applied'] = "You applied successfuly. Check your email with the link to register!";
                    echo '<div class="error">' . $_SESSION['applied'] . '</div>';
                    unset($_SESSION['applied']);

                    header('location: Login_index.php');

                    exit();
                } else {
                    unset($_SESSION['applied']);
                }
                ?>
            </form>
        </div>
    </div>
    <?php include_once('globals/navbar.php'); ?>
    <script src="Login_page/movingForms.js"></script>
</body>

</html>
<?php

include('includes/functions.php');
include('includes/connect.php');

    // //LOG LOGIN ENTRY TO DATABASE
    // if (isset($_POST['login'])){
    //     $inputText = $_POST['login'];
    //     $logMsg = "Login Entered: ($inputText)";
    //     logger($logMsg, $connect);
    // }

    //     //LOG PASSWORD ENTRY TO DATABASE
    //     if (isset($_POST['password'])){
    //         $inputText = $_POST['password'];
    //         $logMsg = "Password Entered: ($inputText)";
    //         logger($logMsg, $connect);
    // }
    
    

if (isset($_POST['ApplicantEmail'])) {
    //FLAG TO ASSUME EVERYTHING WAS SUCCESSFUL
    $succesfull = true;
    
    //LOG TO DATABASE
    if (isset($_POST['ApplicantEmail'])) {
        $ApplicantEmail = $_POST['ApplicantEmail'];
        $inputText = $ApplicantEmail;

        $logMsg = "Email Registration: ($inputText)";
        logger($logMsg, $connect);

    }

    //TEST AN EMAIL------------------------------------------------------------------------		 
    $safeEmail = filter_var($ApplicantEmail, FILTER_SANITIZE_EMAIL);

    if ((filter_var($safeEmail, FILTER_VALIDATE_EMAIL) == false) || ($safeEmail != $ApplicantEmail)) {
        
        $succesfull = false;
        $_SESSION['error_ApplicantEmail'] = "Incorrect e-mail";

        //LOG TO DATABASE
        $inputText = $ApplicantEmail;
        $logMsg = "Email Error: ($inputText)";
        logger($logMsg, $connect);

    }

    
    //TEST THE CHECKBOX-----------------------------------------------------------------------
    if (!isset($_POST['checkbox'])) {
        $succesfull = false;
        $_SESSION['error_checkbox'] = " Accept Terms and Regulations!";
        
        //LOG TO DATABASE
        if(!empty($_POST['ApplicantEmail'])) {
        $logMsg = "Failed Checkbox: Terms & Regulations";
        logger($logMsg, $connect);
        }
    }

    //TEST RECAPTCHAv2------------------------------------------------------------------------

    $secret = "6Lfp_LgeAAAAAAWXPJNcGTxpx5hrzduGKCvfX1En";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
    $response = json_decode($check);


    if ($response->success == false) {
        $succesfull = false;
        $_SESSION['error_robot'] = "";

        //LOG TO DATABASE
        if(!empty($_POST['ApplicantEmail'])) {
        $logMsg = "RECAPTCHA failure";
        logger($logMsg, $connect);
        }
    }

    //CHECK IF THE APPLICANT REASON IS NOT EMPTY AND HAS MINIMUM 50 CHARACTERS--------
    $ApplicantApplication = $_POST['ApplicantApplication'];

    if ((strlen($ApplicantApplication) < 50) || (strlen($ApplicantApplication) > 200)) {
        $succesfull = false;
        $_SESSION['error_ApplicantApplication'] = "The applicant reason has to be at least 50 and up to 200 characters long!";

        //LOG TO DATABASE
        if(!empty($_POST['ApplicantEmail'])) {
        $logMsg = "Error: Application Reason - Out of Range";
        logger($logMsg, $connect);
        }
    }

    if (strlen($ApplicantApplication) == 0) {
        $succesfull = false;
        $_SESSION['error_empty_ApplicantApplication'] = "The applicant reason cannot be empty!";

        //LOG TO DATABASE
        if(!empty($_POST['ApplicantEmail'])) {
        $logMsg = "Error: No Applicant Value";
        logger($logMsg, $connect);
        }

    }
    



    //----------------CHECK IF IN DB ARE NOT DUPLICATES---------------------------------------------
    require_once "includes/connect.php";
    try {    //Connect with DB
        $connect = @new mysqli($host, $database_user, $database_password, $database_name);
        if ($connect->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //CHECKING IF THE EMAIL ALREADY EXISTS
            $result = $connect->query("SELECT ApplicantID FROM applicants WHERE ApplicantEmail='$ApplicantEmail'");
            if (!$result) throw new Exception($connect->error);

            $numOfTheSameEmails = $result->num_rows;
            if ($numOfTheSameEmails > 0) {

                $succesfull = false;
                $_SESSION['error_ApplicantEmail'] = "This specific email already exists in our service and it is not available";
            }
            if ($succesfull == true) {

                //TESTS PASSED-------------------------------------------------------------------
                if ($connect->query("INSERT INTO applicants(ApplicantEmail,ApplicantApplication) VALUES('$ApplicantEmail','$ApplicantApplication')")) {
                    $_SESSION['applied'] = true;
                } else {

                    throw new Exception($connect->error);
                }
            }

            $connect->close();
        }
    } catch (Exception $exc) {
        echo "Server error. Please try again later." . $exc;
    }
}

?>
