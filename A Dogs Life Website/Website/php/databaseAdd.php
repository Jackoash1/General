<?php
require_once("../db/DB_connect1.php"); 


// connect to the database

$errors = array(); 

// REGISTER USER
if (isset($_POST['databaseAdds'])) {



// Create the Table in MySQL Databases
$sql = "CREATE TABLE IF NOT EXISTS product(
    Username  VARCHAR(35) NOT NULL,
    password VARCHAR(35) NOT NULL,
    Email_Address VARCHAR(50) NOT NULL,
    Role int(1) NOT NULL
)";
if(mysqli_query($db, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}


  // receive all input values from the form
  
  $user_name = mysqli_real_escape_string($db, $_POST['uname']);
  $pass = mysqli_real_escape_string($db, $_POST['psw']);
  $email_addr = mysqli_real_escape_string($db, $_POST['eml']);

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
 
  $user_check_query = "SELECT * FROM users WHERE Username='$user_name' OR Email_Address='$email_addr' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['Username'] === $user_name) {
      array_push($errors, "Username already exists");
    }

    if ($user['Email_Address'] === $email_addr) {
      array_push($errors, "There is already an account with that email address");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	

  	$query = "INSERT INTO users (Username, Password, Email_Address, Role) VALUES ('$user_name', '$pass','$email_addr','2')";
  	
    mysqli_query($db, $query);
  	
  	header('location: ../html/databaseAdds.html');
  }
}
?>
// ... 