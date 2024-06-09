<html>

<?phpinclude_once('include/register.inc.php')?>

<head>
    <link rel="stylesheet" href="styles/signup_stylesheet.css">
    <title> Signup </title>

</head>

<body>
    <h1>Signup</h1>
    <!-- this allows the user to input their own username, password and email and should go to the database to submit all of this -->
    <div class="loginForm">
        <form action="includes/register.inc.php" method="POST">
            <label for="uname"><b>Name:</b></label>
            <input type="text" name="username" placeholder="First and Last name" required><br>

            <label for="pswd1"><b>Password:</b></label>
            <input type="password" name="password" placeholder="Password" required><br>
            <label for="pswd2"><b>Confirm Password:</b></label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <span id='message'></span>

            <label for="eml"><b>Email:</b></label>
            <input type="text" name="email" placeholder="Email" required><br>

            <label for="academicID"><b>Academic ID:</b></label>
            <input type="text" name="academicID" placeholder="Academic ID" required><br>

            <label for="academicTitle"><b>Academic Title:</b></label><br>
            <input type="radio" id="underGrad" name="academicTitle" value="Undergraduate">
            <label for="underGrad">Undergraduate</label><br>
            <input type="radio" id="postGrad" name="academicTitle" value="Post Graduate">
            <label for="underGrad">Post Graduate</label><br>
            <input type="radio" id="phdStudent" name="academicTitle" value="PhD Student">
            <label for="phdStudent">PhD Student</label><br>
            <input type="radio" id="prof" name="academicTitle" value="Professor">
            <label for="prof">Professor</label><br><br>

            <label for="institute"><b>Academic Institution:</b></label>
            <input type="text" name="institution" placeholder="Academic Institution" required><br>

            <button type="submit" name="submit">Signup</button>
            <div class="signup">

                <!-- Web link to sign in if the user already has an account -->
                <p>Already have an account?</p>
                <p><a href="Login_index.php">Sign in</a></p>
        </form>
    </div>
</body>

</html>
