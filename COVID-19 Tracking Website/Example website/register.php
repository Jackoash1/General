<?php include_once 'header.php' ?>

<!-- Registration Forms -->
<div class="wrapper">

    <div class="base-box container-sm form-box shadow-std" id="signupContainer">
        <h1>Sign Up</h1>
        <form action="includes/register.inc.php" method="POST">

            <!-- The username field + label -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="John_Doe" id="username" class="form-control" required>
                <div class="invalid-feedback">Username Invalid!</div>
            </div>

            <!-- 2 columns for the password + password confirmation -->
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" placeholder="1234 etc." id="password" class="form-control" required>
                        <div class="invalid-feedback">Password Invalid!</div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="conf_password">Password:</label>
                        <input type="password" name="conf_password" placeholder="Confirm Password" id="conf_password" class="form-control" required>
                        <div class="invalid-feedback">Passwords Don't Match!</div>
                    </div>
                </div>

            </div>

            <!-- Email+ email label -->
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" name="email" placeholder="John_Doe@gmail.com" id="email" class="form-control " required>
                <div class="invalid-feedback">Email Invalid!</div>
            </div>

            <!-- Terms and Conditions + Sign-up button -->

            <div class="row">

                <div class="col-md-6 col-sm-12">
                    <div class="form-check form-group text-center">
                        <input type="checkbox" id="tosCheckbox" class="form-check-input" required>
                        <label class="form-check-label" for="tosCheckbox">I agree to the terms and conditions</label>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="align-self-center text-center">
                        <button class="btn btn-dark shadow-std" name="submit" type="submit">Sign Up</button>
                    </div>
                </div>

            </div>

        </form>

        <h2 id="empty-input-msg" class="hidden">Fill All Fields!</h2>
        <h2 id="taken-details-msg" class="hidden">Username or Email Taken!</h2>
        <h2 id="success-msg" class="hidden">Registration Complete</h2>

    </div>
</div>

<?php
if (isset($_GET["error"])) {

    switch ($_GET["error"]) {

        case "none":

            echo ' <script type="text/javascript">
            $(document).ready(function() {
                $("#success-msg").removeClass("hidden");
            }); 
            </script>';

            break;

        case "usertaken":
            echo ' <script type="text/javascript">
            $(document).ready(function() {
                $("#taken-details-msg").removeClass("hidden");
            }); 
            </script>';

            break;
        case "invalidusername":

            echo ' 
            <script type="text/javascript">
            $(document).ready(function() {
                $("#username").addClass("is-invalid");
            }); 
            </script>';

            break;

        case "invalidemail":

            echo '
             <script type="text/javascript"> 
            $(document).ready(function() {
                $("#email").addClass("is-invalid");
            }); 
            </script>';

            break;

        case "shortpassword":

            echo ' 
            <script type="text/javascript">
            $(document).ready(function() {
                $("#password").addClass("is-invalid");
            });  
            </script>';

            break;

        case "passwordmismatch":

            echo ' 
            <script type="text/javascript"> 
            $(document).ready(function() {
                $("#conf_password").addClass("is-invalid");
            }); 
            </script>';

            break;

        case "emptyinput":

            echo ' 
                <script type="text/javascript"> 
                $(document).ready(function() {
                    $("#empty-input-msg").removeClass("hidden");
                }); 
                </script>';

            break;

        default:
    }
}

?>

<?php require_once 'footer.php' ?>