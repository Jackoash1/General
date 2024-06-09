<?php require_once 'header.php' ?>

<div class="wrapper">
    <!-- This is the login screen for our website -->
    <div class="base-box container-sm form-box shadow-std" id="loginContainer">

        <h1>Log In</h1>

        <form action="includes/login.inc.php" method="POST">

            <!-- The username + username label -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Username or Email" id="username" class="form-control" required>
                <div class="invalid-feedback">Username Invalid!</div>
            </div>

            <!-- The password + password label -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="1234 etc." id="password" class="form-control" required>
                <div class="invalid-feedback">Password Invalid!</div>
            </div>

            <div class="align-self-center text-center">
                <button class="btn btn-dark shadow-std" name="submit" type="submit">Log In</button>
            </div>
        </form>
        <h2 id="empty-input-msg" class="hidden">Login incomplete!</h2>
    </div>
</div>

<?php
if (isset($_GET["error"])) {

    switch ($_GET["error"]) {

        case "emptyinput":

            echo ' 
                <script type="text/javascript"> 
                $(document).ready(function() {
                    $("#empty-input-msg").removeClass("hidden");
                }); 
                </script>';
            break;

        case "invalidpassword":
            echo ' 
            <script type="text/javascript"> 
            $(document).ready(function() {
                $("#password").addClass("is-invalid");
            }); 
            </script>';
            break;

        case "usernotfound":
            echo ' 
            <script type="text/javascript"> 
            $(document).ready(function() {
                $("#username").addClass("is-invalid");
            }); 
            </script>';

            break;

        default:
    }
}
?>

<?php require_once 'footer.php' ?>