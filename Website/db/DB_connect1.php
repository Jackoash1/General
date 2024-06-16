<?php

//$hostname= "localhost";
//$username="your username";
//$dbname ="your Database name";
//$password= "your password";

//access MySQL database via LAMP

   $hostname= "localhost";
   $username="asadik";              /* username and your database name are the same */
   $dbname ="asadik";
   $password= "**********";

//access MySQL database via XAMPP

$hostname= "localhost";
$username="root";
$dbname ="it_coursework";
$password= "";

//$mysqli = new mysqli($hostname, $username, $password, $dbname);
$db = mysqli_connect($hostname, $username, $password, $dbname);

// Check connection
if($db === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
?>
